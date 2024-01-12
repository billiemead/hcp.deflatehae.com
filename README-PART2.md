![TheAgencyNetwork_logo-long-215x36](https://thewhyse.github.io/tutorial-images/TheAgencyNetwork_logo-long-215x36.png)

# Development - XD File to Simple WordPress Page using WP Bakery Page Builder & SCSS Styling

After planning, there are three (3) distinct aspects and primary sprints of our dev workflow... **LAYOUT**, **CONTENT** and **STYLING**. These are in place and allow us to get around the limitations of the WordPress framework which include the inability to easily version the database. A simple rule to remember is `code` is always pushed forward and the `database` is always pushed back amongst the environments.

**LAYOUT:**  This is where developers translate the **XD** file layouts provided by the **Design Department** to Desktop, Tablet & Mobile web layouts using rows, columns, flexbox & CSS grid depending on the needs of the page/section. For smaller projects, it's acceptable to complete both the initial **LAYOUT & CONTENT** sprints together but for larger, multi-page projects, it better to keep them as separate sprints. As three layouts (desktop, tablet and mobile) are required for every page. Please always keep in mind, our agile approach emphasizes continuous collaboration, improvement and incremental and iterative steps. So, we are always trying to keep the workflow moving between the cycles of developing, reviewing, dev changes and updates, and reviewing again.

The layouts are built by first creating a new WordPress page and then using either the [WP Bakery](https://wpbakery.com/) or [Divi](https://www.elegantthemes.com/gallery/divi/) page builder to block out a responsive structure. Ids and classes need to be added to all layout elements and no page builder controls are to be used for ANY styling whatsoever (margins, padding, etc.).

**CONTENT:** Copy, text, headlines, paragraphs, images, icons, and other assets. These are added into the layout using the page builder text block, image block, icon block, or for more complex elements, we may use an HTML block. But wherever possible the goal is to retain the ability for non-developers to easily edit page elements and content. As above, ids and classes need to be added to all content elements and absolutely no page builder controls are to be used for styling (font size, line-height, colors, et.). When it comes to images, icons, PDFs, videos or any other layout assets, the WordPress media library is NOT to be used. Instead, inside the child-theme folder, are separate folders for each asset type and you will store and link to assets in these folders. But again, under no circumstances should any styling be done at this point or by using the page builder controls. The content is then sent to EDIT for review. Once the Edit cycle has completed, the content is LOCKED and no other changes should ever be made to it.

**STYLING:** Once the section/page/site Layout and Content as well as element selectors are in place, we can begin styling. Nested SCSS is used for all styling, and we will be working with the `.scss` files located and organized in the `src/scss` folder, which is located within the child theme folder:

Freelance developers will be primarily working on sections and pages, so for the most part, all `.scss` files you will be working with will be located in the pages folder.

**pages folder:**
`deflate-hae.scss` Home page
(all other pages as needed for project by name)

The other `.scss` files are already set up during project on-boarding and rarely will need to be changed, unless you'd like to add a `mixin` for semantic re-use. But they are covered here for reference.

**base folder:**
`base.scss` (usually just used for header & footer logos and possibly other base styles)
`colors.scss` (project colors, text drop shadow mixins)
`fonts.scss` (project font mixins for family, size & weight)
optional/project dependent:
`hovers.scss`

**layout folder:**
`header.scss`
`footer.scss`
`layout.scss` (css reset, etc)
`mobilefooterbar.scss` (icon footer bar for mobile only)
`navigation.scss` (desktop & mobile navigation, menus, sub-menus)

**modules folder:**
`animations.scss` (CSS for animations)
`buttons.scss`
`forms.scss` (CSS for forms)
optional/project dependent:
`carousel.scss`

All .scss starting files contain color and font @imports, comments indicating the start and end of the styling (just in case the minified CSS file ever needs to be manually reviewed) and empty media queries for the breakpoints we use:

1920px      Desktop
1200px      Laptop (most clients review using this screen size!)
1024px      Tablet Landscape
768px       Tablet Portrait
576px       2nd Generation Mobile
480px       1st Generation Mobile
320px       Legacy Mobile

Not every breakpoint needs to be used for every layout/section, but they are there if needed.  And while it may seem out of date, it very important to check and if necessary, provide styling for 320px devices.

XD files are provided by the Art Department and will contain layouts and artwork assets formatted for:

1920px
1024px
576px

It is up to us developers to 'fill in the blanks' for in-between sizes if necessary, or if layouts break, text/copy crashes, etc. But it is VERY rarely, if ever, that all seven breakpoints listed above will be used for any given page/section/module/element.


**Sample Developer daily workflow**
- Review daily assignment(s) with dev lead
- Sync your local git repository with remote
- Git checkout and/or create (branch)
- Commit your changes often with short, but decipherable comments
- When you are ready to share, make a pull request
- Ask me to review your pull request and get approval
- Merge your code into master

**Requirements:**
[Adobe Photoshop, Illustrator & XD](https://www.adobe.com/products/catalog.html#category=creativity-design)
If you do not have a Creative Cloud subscription, you will need one for freelance assignments with The Agency Network, but for the purposes of this tutorial you may sign up for a free 7 day trial
[Git](https://git-scm.com/downloads)
[GitHub](https://github.com/)
[Patheon](https://pantheon.io/)
[Slack](https://the-agencynetwork-mjh.slack.com)

## Appendix

[1. Layout ](#1-build-layout)
[2. Content](#2-add-content)
[3. Styling](#3-add-styling)
[4. Build Code](#4-build-code)
[5. Git Commit/Push ](#5-git-commit)


- - - -

## 1 Layout ##

#### Build Layout Structure ####

1. Login to **WordPress Admin**** LOCALLY... do not log into the **Patheon** instance yet please! You will build your page, add content and style it using your local dev environment... and as the very last step, will copy the page to the **WordPress** instance on the **DEV server on Pantheon** and then push your CSS code via `git`.

2. Go to `Pages -> Add New` and create a new **WordPress** page using your name as the page name/slug

3. Make sure that the **WP Bakery Backend Editor** is selected and then choose the **Theme Layout Option** in the editor:

    ![](https://thewhyse.github.io/tutorial-images/dev-test-b-1.png)

    ![](https://thewhyse.github.io/tutorial-images/dev-test-b-2.png)


4. Build your LAYOUT using the page builder structure elements (rows, columns, etc.). Block it out and just focus on the Desktop 1920px layout.... there is no need to also build a responsive mobile layout unless you really want to be impressive 🙂 But it's not expected. Please be sure to remember the golden rule, which is to not use any of the page builder styling controls, but do add classes, ID's and other selectors so you may target elements with CSS/SCSS later.
> here's a tip... I add my own classes and ID's to even the rows and columns, as then I have complete control over the the responsive styling. And never use any of the default elements sectors as they are dynamic and change from layout to layout or environment to environment.


- - - -

## 2 Content ##

#### Add Page Content ####


1. Add the CONTENT using the page builder text and image blocks matching what is in the XD layout and assign classes and IDs as above for styling next. For images, please do not use the WordPress media library, but put your image assets in the `/wp-content/themes/conall-child/images` folder instead.   And then when adding the page builder image block, choose the external link option, and use the path above. You don't need to include the domain name in your image link address, or your image links will break once you move the layout to the Patheon DEV site... just use `/wp-content/` on, and it'll work fine.

- - - -

## 3 Styling ##

#### Style layout using CSS/SCSS ####

1. Once the LAYOUT and CONTENT is in place using the WP admin backend, you are ready to start styling. Open your code editor and navigate to the `/wp-content/themes/conall-child/` folder. As you installed the npm packages previously, the first step is to start the script that will automatically watch for CSS/SCSS changes and display them in real time. In your terminal run `npm run watch` and after a few minutes, you will see

`[08:20:27] Starting 'watchForChanges'...`

Which means you are ready to begin. Obviously, you need to keep this terminal window open, and for other steps you will need to open other terminal instances, so that this can always be running until you conclude the project.

2. Navigate to the `/conall-child/src/scss/pages` folder and you will see an `.scss` file with you name on it.  This is for your styling!

3. Open a new terminal window and also navigate to the `/wp-content/themes/conall-child/` folder. Once there please run ` git checkout -b <yourname>`  and you will now have your own git branch to commit. As you style, please commit frequently as you see fit and when your styling is at a point where you are satisfied with what you see in the browser.

- - - -

## 4 Build Code ##

#### Gulp Build ####

1. When you are happy with what you see and are ready to submit it, open a new terminal window and navigate to the `/wp-content/themes/conall-child/` folder. From the terminal please run `gulp build --prod` and give it a few minutes to complete. If there are no errors in your CSS, it will build a minified and autoprefixed `bundle.scss` file for distribution.

- - - -

## 5 Git Push ##

#### Push your changes to GitHub and Patheon and transfer your page code to the Pantheon WordPress Instance ####

1. When you are ready, from the terminal and in the root project folder,`git push -u origin <yourbranchname>` and when it completes, go to GitHub and create a pull request.

2. The last step is to:
- log into the WordPress instance on the [DEV server on Pantheon](http://dev-pharvaris-deflate-hcp.pantheonsite.io/wp-admin/)
- create a new WordPress page on the instance on the DEV server, mirroring the same name you used locally
- Now, for both LOCAL and DEV instances, change the Page Builder mode from Backend Editor to Classic Mode
    ![](https://thewhyse.github.io/tutorial-images/dev-test-b-3.png)
- At the far right of the editor box, make SURE that you click the text tab! If it is on Visual, and you try to cut and paste the code from LOCAL to DEV, it will not work!
 ![](https://thewhyse.github.io/tutorial-images/dev-test-b-4.png)
- Select, copy and paste all of the page builder code from your LOCAL page to the DEV page and save.

- - - -

Reach out to me anytime on Slack or [email](mailto:bmead@mjhlifesciences.com)


## Authors

- [@billiemead](https://www.github.com/billiemead)

## 🔗 Links

[![githib](https://img.shields.io/badge/github-000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/ProcureAbility-com/development.procureability.com/)

[![wpengine](https://img.shields.io/badge/wpengine-0ECAD4?style=for-the-badge&logo=wpengine&logoColor=white)](https://wpengine.com/)

