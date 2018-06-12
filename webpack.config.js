let Encore = require("@symfony/webpack-encore");

Encore
    // the project directory where all compiled assets will be stored
    .setOutputPath("public/build/")

    // the public path used by the web server to access the previous directory
    .setPublicPath("/build")

    // will create public/build/app.js and public/build/app.css
    .addEntry("app", "./public/assets/js/base.js")

    .enableBuildNotifications()
;

// export the final configuration
module.exports = Encore.getWebpackConfig();