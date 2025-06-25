import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";


/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        './resources/**/*.js',
        "./node_modules/flowbite/**/*.js"
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [
        forms,
        require("flowbite/plugin")
    ],
};
