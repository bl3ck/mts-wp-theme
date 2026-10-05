Michael Taiwo Scholarship
=========================

A custom theme based on \_tw

## Quickstart

### Installation

1. Move this folder to `wp-content/themes` in your local development environment
2. Run `npm install && npm run dev` in this folder
3. Activate this theme in your local WordPress installation

### Development

4. Run `npm run watch`
5. Add [Tailwind utility classes](https://tailwindcss.com/docs/utility-first) with abandon

### Deployment

6. Run `npm run bundle`
7. Upload the resulting zip file to your site using the “Upload Theme” button on the “Add Themes” administration page

You can also use `make` for common release tasks:

* `make build` runs the production asset build
* `make zip` increments the patch version, rebuilds assets, and creates a versioned archive like `michael-taiwo-scholarship-0.1.3.zip`
* `make bump-minor` increments the source version like `0.1.1 -> 0.2.0`
* `make bump-major` increments the source version like `0.1.1 -> 1.0.0`
* `make release` runs `make zip` and copies that same versioned zip into `dist/`
* `make version` prints the current source version

Or [deploy with the tool of your choice](https://underscoretw.com/docs/deployment/#h-other-deployment-options)!

## Winner Administration

1. Add mentor records under **Mentors > Add New** and publish them to make them available for assignment. Mentors are admin-only records, not public profiles.
2. Edit a winner and use **Scholar Record (Admin Only)** to select their assigned mentor and enter their graduate school, program, enrollment city/region, enrollment country, and scholarships awarded. Each winner can have one mentor; a mentor can have many winners. Mentor edit screens show the first 20 accessible assigned winners and link to the full filtered list.
3. In **Winners**, select any combination of Status, graduate school, program/degree, enrollment city, enrollment country, scholarship, mentor, and existing taxonomy filters, then click **Filter**. Search and WordPress post-status/date filters also apply.
4. Click **Export filtered winners (CSV)** to download every matching record, across all pages. Leaving filters empty exports all accessible non-trashed winners. Export is restricted to users with both `manage_options` and winner-editing permission, normally administrators.

Enrollment Country and Enrollment City / Region describe the graduate institution, not the winner's home address or country of origin. Use **Status: Enrolled** plus **Enrollment Country: United States** or **United Kingdom** to find current students there, and optionally narrow by city. The existing `current_country` and `current_location` storage keys are retained; review previously entered residence values for enrollment accuracy.

School, program, city, and country dropdowns use saved values; enter consistent spellings so equivalent values appear together. **Scholarships Awarded** allows multiple reusable awards per winner, e.g. Mastercard Foundation Scholars Program and Erasmus Mundus. Add or select awards within the winner record; manage their names under **Winners > Scholarships Awarded**. Scholarship filters and CSV exports use these same awards.

The CSV contains private scholar data, including contact email and private notes. Handle it accordingly. The new fields and mentor assignments are not added to public winner templates or REST responses.

Run the dependency-free regression checks with `php tests/winner-admin.php`. These cover field registration, combined filters, export permissions, CSV safety, and multi-page exports; they do not replace testing in a running WordPress admin session.

## Full Documentation

### Fundamentals

* [Installation](https://underscoretw.com/docs/installation/)  
  Generate your custom theme, install it in WordPress and run your first Tailwind builds
* [Development](https://underscoretw.com/docs/development/)  
  Watch for changes, build for production and learn more about how _tw, WordPress and Tailwind work together
* [Deployment](https://underscoretw.com/docs/deployment/)  
  Share your new WordPress theme with the world
* [Troubleshooting](https://underscoretw.com/docs/troubleshooting/)  
  Find solutions to potential issues and answers to frequently asked questions

### In Depth

* [Using Tailwind Typography](https://underscoretw.com/docs/tailwind-typography/)  
  Customize front-end and back-end typographic styles
* [JavaScript Bundling with esbuild](https://underscoretw.com/docs/esbuild/)  
  Install and bundle JavaScript libraries (very quickly)
* [Adding custom fonts](https://underscoretw.com/docs/custom-fonts/)
  Host your fonts yourself or use a third party—and then add those fonts to your WordPress theme
* [Linting and Code Formatting](https://underscoretw.com/docs/linting-code-formatting/)  
  Catch bugs and stop thinking about formatting
* [Keeping your theme up-to-date](https://underscoretw.com/docs/updating/)
  How to update (and whether or not you should)

### Extras

* [On Tailwind and WordPress](https://underscoretw.com/docs/wordpress-tailwind/)  
  Understand how WordPress and Tailwind work together
* [Styling HTML from outside the theme](https://underscoretw.com/docs/styling-html-from-outside-the-theme/)
  Work with WordPress core, plugins and JavaScript libraries
* [Managing Styles for Custom Blocks](https://underscoretw.com/docs/custom-blocks/)  
  Learn strategies for using Tailwind in theme-specific custom blocks
* [Setting Up Browsersync](https://underscoretw.com/docs/browsersync/)  
  Add live reloads and synchronized cross-device testing to your workflow
