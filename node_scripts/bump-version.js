#!/usr/bin/env node

const fs = require('fs');
const path = require('path');

const releaseType = process.argv[2] || 'patch';

const versionFiles = [
	{
		filePath: path.join(__dirname, '../tailwind/custom/file-header.css'),
		pattern: /(Version:\s*)(\d+)\.(\d+)\.(\d+)/,
		type: 'header',
	},
	{
		filePath: path.join(__dirname, '../theme/functions.php'),
		pattern: /(define\( 'MT_VERSION', ')(\d+)\.(\d+)\.(\d+)(' \);)/,
		type: 'constant',
	},
];

function readVersion(filePath, pattern) {
	const content = fs.readFileSync(filePath, 'utf8');
	const match = content.match(pattern);

	if (!match) {
		throw new Error(`Could not find a version string in ${filePath}`);
	}

	return {
		content,
		match,
		version: `${match[2]}.${match[3]}.${match[4]}`,
	};
}

function incrementVersion(version, type) {
	const [major, minor, patch] = version.split('.').map(Number);

	if ([major, minor, patch].some(Number.isNaN)) {
		throw new Error(`Version must use major.minor.patch format. Received: ${version}`);
	}

	if (type === 'patch') {
		return `${major}.${minor}.${patch + 1}`;
	}

	if (type === 'minor') {
		return `${major}.${minor + 1}.0`;
	}

	if (type === 'major') {
		return `${major + 1}.0.0`;
	}

	throw new Error(`Unsupported release type: ${type}`);
}

function updateContent(type, match, nextVersion) {
	if (type === 'header') {
		return `${match[1]}${nextVersion}`;
	}

	return `${match[1]}${nextVersion}${match[5]}`;
}

const fileStates = versionFiles.map(({ filePath, pattern, type }) => ({
	filePath,
	type,
	...readVersion(filePath, pattern),
}));

const versions = [...new Set(fileStates.map(({ version }) => version))];

if (versions.length !== 1) {
	throw new Error(`Version files are out of sync: ${versions.join(', ')}`);
}

if (!['patch', 'minor', 'major'].includes(releaseType)) {
	throw new Error(`Release type must be one of: patch, minor, major. Received: ${releaseType}`);
}

const currentVersion = versions[0];
const nextVersion = incrementVersion(currentVersion, releaseType);

fileStates.forEach(({ filePath, content, match, type }) => {
	const updatedContent = content.replace(match[0], updateContent(type, match, nextVersion));
	fs.writeFileSync(filePath, updatedContent);
});

console.log(`Bumped ${releaseType} theme version: ${currentVersion} -> ${nextVersion}`);