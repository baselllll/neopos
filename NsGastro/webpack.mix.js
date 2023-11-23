const { unlinkSync, existsSync, writeFileSync } = require('fs');
let mix     =   require( 'laravel-mix' );
const path  =   require( 'path' );
require('mix-tailwindcss');

mix.webpackConfig({
    resolve: {
        extensions: [ "*", ".js", ".jsx", ".vue", ".ts", ".tsx"],
        alias: {
            '@': path.resolve( __dirname, 'Resources/ts/')
        }
    }
})

mix.disableNotifications();

mix.vue();
mix.sass("Resources/css/gastro.scss", "css" )
    .tailwind();

if ( mix.inProduction() ) {
    mix.ts( 'Resources/ts/Gastro.ts', 'js' );
    mix.ts( 'Resources/ts/GastroKitchen.ts', 'js' );
    mix.minify( 'Public/js/Gastro.js' ).version();
    mix.minify( 'Public/js/GastroKitchen.js' ).version();
} else {
    mix.ts( 'Resources/ts/Gastro.ts', 'js' ).version();
    mix.ts( 'Resources/ts/GastroKitchen.ts', 'js' ).version();
}

mix.after( () => {
    if ( mix.inProduction() ) {
        unlinkSync( `${__dirname}/Public/js/Gastro.js` );
        unlinkSync( `${__dirname}/Public/js/GastroKitchen.js` );
        
        if ( existsSync( `${__dirname}/.dev` ) ) {
            unlinkSync( `${__dirname}/.dev` );
        }
    } else {
        writeFileSync( `${__dirname}/.dev`, '' );
    }
});

mix.setPublicPath( 'Public' );