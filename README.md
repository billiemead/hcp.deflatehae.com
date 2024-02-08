![TheAgencyNetwork_logo-long-215x36](https://thewhyse.github.io/tutorial-images/TheAgencyNetwork_logo-long-215x36.png)

## Tech Stack
thewhyse/pharvaris-deflate-hcp is built on the following main stack:

- [gulp](http://gulpjs.com/) – JS Build Tools / JS Task Runners
- [Ruby](https://www.ruby-lang.org) – Languages
- [PHP](http://www.php.net/) – Languages
- [Sass](http://sass-lang.com/) – CSS Pre-processors / Extensions
- [Hack](http://hacklang.org/) – Languages
- [JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript) – Languages
- [PHPUnit](https://phpunit.de/) – Testing Frameworks
- [Autoprefixer](https://github.com/postcss/autoprefixer) – CSS Pre-processors / Extensions
- [Babel](http://babeljs.io/) – JavaScript Compilers
- [ESLint](http://eslint.org/) – Code Review

Full tech stack [here](/techstack.md)

# Local Development Environment Set-up

Following is the local environment setup to prepare for development for client sites hosted with [Pantheon](https://pantheon.io/). If you are not familiar with the **Patheon DevOps workflow**, every website comes with three environments: **Dev, Test, and Live**. Each environment runs a version of the site on its own container. We will only ever be working with the **DEV** environment and while **Patheon** offers developers the option to use either a **Git** or **SFTP** workflow, we will only use **Git**. Please do not ever switch the **Development Mode** to **SFTP** from the **Patheon** dashboard, as it cannot be switched back and commits as well as WIP could be lost.

Separate **Dev, Test, and Live** environments allow us to develop and test our client websites without ever touching or impacting the **Live** environment's availability to the world. In addition to the three **Patheon** environments, each developer also needs to set up an additional local development environment, import a copy of the **MySQL** database, copy down the starting assets and get **GitHub** configured.

Each developer needs to have a local dev environment because this is where all daily work is done. Once set up, you will probably never leave the child theme folder, as this is where 80% of your development will be done.  This is where our code will be built, minified, optimized, autoprefixed, sourcemaps created and images compressed. The other 20% will be spent inside the **WordPress** admin, using the Page Builders to build your layout, add text and asset blocks, and assign styling selectors.

It is very important to remember that the **DEV** environment is the single source of truth for code & content as the **LIVE** environment is for the database. Never, ever rely on your local dev environment for either as you may not have the most recent changes from another developer. And the page **LAYOUT** and **CONTENT** cannot be pulled to your local environment the same way that code can be (using `git pull` or `git fetch`). So, at the start of your day, or when instructed by another developer, it's a good idea to copy down the **DEV** page **LAYOUT** and **CONTENT** to your local **WordPress** installation environment. In the case where multiple developers are working on the same project, each dev will be assigned to work on a different page. If multiple developers are assigned to work on the same page, each will be assigned a different section, and each section will be built using separate and temporary **WordPress** pages. When completed, the sections will be saved to the different page builders libraries, and then the sectcombined into a **MASTER** and final page.

It's actually not as confusing in practice as it may initially sound. But the reason that developers need to be kept separate is simply to avoid one from overwriting the other while working.

**To make an analogy to a VCS (version control system)**
- working on separate pages would be equivalent to working with different git branches
- saving the pages to a library when completed would be equivalent to doing a Pull Request (notifing team members that they have completed a feature and asking for a team review)

**Requirements:**
- Apache/Nginx
- PHP 8.1
- MySQL
- Ability to run a local environment that supports the [WordPress](https://wordpress.org/download/) framework:
[Docker](https://www.docker.com/)
[XAMPP](https://www.apachefriends.org/download.html)
[Lando](https://lando.dev/)
[Laragon](https://laragon.org/download.html)




## Appendix

[1. Git Clone ](#1-git-clone)

[2. Prepare Database](#2-prep-database)

[3. Edit Local Files](#3-edit-local-files)

[4. Git Set-up](#5-git-setup)

[5. NPM](#6-npm-install)

[6. More Info](#7-more-info)

- - - -

## 1 Git Clone ##

#### Make a Local Development Copy of the website ####

1. Login to Pantheon [Admin](https://dashboard.pantheon.io/)

2. From Upper Top Left Menu - Choose `The Agency Network - Clients Workspace`

3. Navigate to `sites -> Pharvaris Deflate HCP (sandbox)`

4. From the DEV environment tab, click the `Clone with Git` button and copy the terminal command:
![](https://thewhyse.github.io/tutorial-images/dev-test-1.png)

5. On your local dev computer, open a terminal window, navigate to your development root folder (www, htdocs, etc.), paste and execute the `git clone` copied from the previous step. This will create a folder `pharvaris-deflate-hcp` containing the WordPress core, plugins, theme and child-theme for the project. It will not contain the uploads folder or database as you will do that later.

6. Return to the Pantheon dashboard, again from the DEV environment tab, select `Database/Files`, and then `Export` from the left-hand menu:
![](https://thewhyse.github.io/tutorial-images/dev-test-2.png)
![](https://thewhyse.github.io/tutorial-images/dev-test-3.png)

7. Create BOTH a Database and Files export sequentially:
![](https://thewhyse.github.io/tutorial-images/dev-test-4.png)

> If you initially see a failed error message in red, look to the very top and if you see and icon spinning next to WORKFLOWS, wait until the task is completed and your downloads should now be available. Direct download both the database and files compressed archives to your local computer.


8.  Open up the `pharvaris-deflate-hcp` folder created from the Git Clone command in step 1
- go into the `wp-content` folder and create a new folder named `uploads`
- uncompress the compressed files archives `pharvaris-deflate-hcp_dev_2023-XX-XXXXX-XX-XX_UTC_files.tar` into the new `uploads` folder
- do not include the `files_dev` folder from the archive, just the contents, so your new folder structure should look like this:
![](https://thewhyse.github.io/tutorial-images/dev-test-5.png)

9. You may delete the compressed files archives `pharvaris-deflate-hcp_dev_2023-XX-XXXXX-XX-XX_UTC_files.tar`

- - - -

## 2 Prep Database ##

#### Create & Import MYSQL Database ####

We will be using the local domain name `https://pharvaris-deflate-hcp.localdev` for this tutorial but feel free to change as you'd like.

1. Open PhpMyAdmin on your local machine, create a new blank MySQL database named `pharvarishcp` and then import the downloaded database file from step 7 above:

2. In `wp-options` table, change `siteurl` and `home` to local domain `pharvaris-deflate-hcp.localdev` or the local development domain you are using.


- - - -

## 3 Edit Local Files ##

#### Create Local Config Files ####

1. Copy the file `pharvaris-deflate-hcp/wp-config-local-sample.php` to create a new file named `pharvaris-deflate-hcp/wp-config-local.php` and set local `MySQL` database credentials, `WordPress salts` and `WP_HOME/WP_SITEURL` as shown below but using your own settings.

2. Enable or disable debugging and add any WordPress defines, that you would like for the LOCAL DEVELOPMENT ENVIRONMENT in the newly created `pharvaris-deflate-hcp/wp-config-local.php` file.

- - - -

## 4 Git Setup ##

#### Git & GitHub Setup ####

1. Got to Windows terminal and navigate to the folder `C:\websites\www\pharvaris-deflate-hcp` or where ever you have the site files. From the terminal please run `git remote add github git@github.com:thewhyse/pharvaris-deflate-hcp.git`

2. In the root project directory `pharvaris-deflate-hcp` go into the `.git` folder and open the `config` file using your favorite code editor. [Visual Studio Code](https://code.visualstudio.com/) is being used for this tutorial.

3. Next, underneath the section labeled `[remote "origin"]` please add the following  `url = git@github.com:thewhyse/pharvaris-deflate-hcp.git` underneath the existing URL, so there are 2 remotes listed under origin (Both Pantheon and GitHub).

3. Save the ​`config` file. Now, anytime you do a `git push -u origin master`, code will be pushed to both GitHub and the Patheon Git repository at the same time and both repos will be synched. But you may still push to GitHub separately as it is listed as its own remote as well.

- - - -

## 5 NPM Install ##

#### NPM Install ####

Requirement: `nodeJS 18.17.0` and `npm 9.8.1`

1. Using your terminal, navigate to the `pharvaris-deflate-hcp\wp-content\themes\conall-child` folder

2. Once you are in the `conall-child` folder, please run `npm install` from the terminal to install all packages

- - - -

## 6 More Info ##

#### And What NOT To Do... ####

* Image file name conventions should follow this format  (all lowercase, no spaces) `descriptive-name-width-x-height.extention` for example: `img-one-300x300.png`

* Every `git push` to the main branch will automatically be deployed to the [Pantheon Development Server](http://dev-pharvaris-deflate-hcp.pantheonsite.io/)

* If you are working on a feature with a set release date, do NOT use the `master` branch... instead, create and use a feature branch.

* Do NOT use SFTP/FTP to change server files or the directory structure

* Do NOT edit files directly on GitHub

Reach out to me anytime on [Slack](https://the-agencynetwork-mjh.slack.com/) or [email](mailto:bmead@mjhlifesciences.com)

## Authors

- [@billiemead](https://www.github.com/billiemead)

## 🔗 Links

[![githib](https://img.shields.io/badge/github-000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/thewhyse/dev-pharvaris-deflate-hcp/)
[![pantheon](https://img.shields.io/badge/pantheon-ffdc28?style=for-the-badge&logo=pantheon&logoColor=black)](https://pantheon.io/)