.DEFAULT_GOAL := help

SLUG := michael-taiwo-scholarship
THEME_DIR := theme
VERSION_FILE := tailwind/custom/file-header.css
DIST_DIR := dist
VERSION ?= $(shell awk -F': ' '/^Version:/ { gsub(/^[[:space:]]+|[[:space:]]+$$/, "", $$2); print $$2; exit }' $(VERSION_FILE))
ZIP_FILE = $(SLUG)-$(VERSION).zip
DIST_ZIP = $(DIST_DIR)/$(ZIP_FILE)

.PHONY: help install bump-version bump-minor bump-major build zip release version clean

help:
	@printf '%s\n' 'Targets:'
	@printf '  %-12s %s\n' 'install' 'Install Node dependencies'
	@printf '  %-12s %s\n' 'bump-version' 'Increment the theme patch version in source files'
	@printf '  %-12s %s\n' 'bump-minor' 'Increment the theme minor version and reset patch to 0'
	@printf '  %-12s %s\n' 'bump-major' 'Increment the theme major version and reset minor and patch to 0'
	@printf '  %-12s %s\n' 'build' 'Run the production asset build'
	@printf '  %-12s %s\n' 'zip' 'Bump version, rebuild assets, and create $(ZIP_FILE)'
	@printf '  %-12s %s\n' 'release' 'Run zip and copy the versioned archive to $(DIST_DIR)/'
	@printf '  %-12s %s\n' 'version' 'Print the current theme version from $(VERSION_FILE)'
	@printf '  %-12s %s\n' 'clean' 'Remove generated zip artifacts'

install:
	npm install

bump-version:
	node node_scripts/bump-version.js

bump-minor:
	node node_scripts/bump-version.js minor

bump-major:
	node node_scripts/bump-version.js major

build:
	npm run production

zip: bump-version build
	node node_scripts/zip.js $(SLUG) $(ZIP_FILE)

release: zip
	@mkdir -p $(DIST_DIR)
	cp $(ZIP_FILE) $(DIST_ZIP)
	@printf 'Created %s\n' '$(DIST_ZIP)'

version:
	@printf '%s\n' '$(VERSION)'

clean:
	rm -f $(SLUG)-*.zip
	rm -f $(DIST_DIR)/$(SLUG)-*.zip