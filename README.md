<!-- AUTO-GENERATED-CONTENT:START (STARTER) -->
<h1 align="center">
  Headless CMS - Frontend and Backend
</h1>

**A <a href="https://www.hyperisland.com/" target="_blank">Hyper Island</a> Student Project**

**About:**

This blog is half the result of exercise in building a headless CMS with PHP using the Slim framework and the Fetch API on the frontend.

Our brief was to simply create a locally-served API that connected to a MySQL schema with news posts and pages, and to use this API in some kind of front-end interface, with both a display page and an /admin page, where an admin could theoretically update and edit the blog. Both ends were meant to be built swappable with another framework or language.

Since it was out of scope, currently, there's no user authentication, no restrictions to the /admin path, or CORS restrictions on the API, so literally anyone can GET / PUT / POST / DELETE from the CMS, which makes either for very bad practice or an amusing social experiment.

**Back-end:**
I initially coded a RESTish API from scratch using PHP,[whose code is here](https://github.com/nehahirve/cms-backend). Later, I switched to a skeleton template from David Eriksson with the Slim framework, to take advantage of its easy routing set up and middleware features.

The API can be accessed at https://vast-fortress-99756.herokuapp.com/api, where there are two endpoints, /posts and /pages, that return JSON responses.

**Front-end:**
I considered using Vue to develop the front-end, [and started here](https://github.com/nehahirve/cms-frontend-vue), but decided to up my react / Gatbsy game instead, using many things for the first time, like the useContext hook, the useMemo hook, a globalContextProvider, wrapping the root element, and Gatsby's new file system route API.

**Things I learnt:**

- PHP
- The PDO object in PHP
- MySQL
- PHPStorm
- DataGrip
- Setting up an API
- The basics of routing
- The Slim framwork
- Deploying a backend app to Heroku
- Some Vue.js (that didn't end up being the framework I went with)
- Newer / more advanced React features
- Axios -- but will favour the Fetch API in the future.

**Development vs Production challenges:**

I've started noting these down, since build / production errors are things I'd like to get better at troubleshooting.

1. Learnt that Heroku can only store the contents of .pem files as environment variables, and the PDO connection to the MySQL database required the filepaths. I eneded up using a createTempFile function in production.

2. Using the react RTE gave me build problems, as the package was already bundled with webpack and Gatsby uses webpack again. I had to learn to provide a custom webpack config to Gatsby.

**Tools and Tech stack:**

[PHP:](https://www.php.net/) “PHP is a popular general-purpose scripting language that is especially suited to web development.”

[Gatsbyjs:](https://www.gatsbyjs.com/) “An open-source framework based on React that helps developers build blazing-fast websites and apps.”

[Slim:](https://www.slimframework.com/) “Slim is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs.”

[Postman:](https://www.postman.com/) "The Collaboration Platform for API Development."

[PhpStorm:](https://www.jetbrains.com/phpstorm/?gclid=Cj0KCQiA34OBBhCcARIsAG32uvNtIGkui4WR0kPpugszk948-QDBOormPQ4oeV2wrspNnJ2DHT5VGpoaAlLpEALw_wcB) "The Lightning-Smart PHP IDE."

[DataGrip:](https://www.jetbrains.com/datagrip/) "Many databases, one tool."

[Heroku:](https://www.heroku.com) "Heroku is a platform as a service (PaaS) that enables developers to build, run, and operate applications entirely in the cloud."

[MySQL:](https://www.mysql.com/) "MySQL Database Service is a fully managed database service to deploy cloud-native applications."

**Libraries and dependencies:**

[axios:](https://www.npmjs.com/package/axios) Promise based HTTP client for the browser and node.js.

[react-markdown:](https://www.npmjs.com/package/react-markdown) Markdown component for React using remark.

[react-rte:](https://www.npmjs.com/package/react-rte) a UI component built completely in React that is meant to be a full-featured textarea replacement, based on raft.js

[masonry:](https://masonry.desandro.com/) JavaScript grid layout library.
