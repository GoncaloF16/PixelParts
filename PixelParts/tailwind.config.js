export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          green: "#1abc9c",    // verde logotipo
          lightblue: "#5dade2", // azul claro
          darkblue: "#154360", // azul escuro
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/line-clamp')
  ],
}
