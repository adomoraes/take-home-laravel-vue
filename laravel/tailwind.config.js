/** @type {import('tailwindcss').Config} */
export default {
	// Verifique se esta secção 'content' está EXATAMENTE assim:
	content: ["./resources/**/*.blade.php", "./resources/js/**/*.vue"],
	theme: {
		extend: {
			// Adicionamos a sua paleta de cores aqui,
			// para que possamos testar com bg-primary
			colors: {
				primary: "#2949BA",
				"dark-accent": "#0C1D59",
				dark: "#1A1E2B",
				"light-bg": "#E7ECFD",
				white: "#FFFFFF",
			},
		},
	},
	plugins: [],
}
