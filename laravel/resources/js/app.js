// Ficheiro: resources/js/app.js
import "../css/app.css"
import Vue from "vue"

// --- ADICIONE ESTAS LINHAS ---
// 1. Crie a pasta /components
// 2. Registe o seu componente "pai"
Vue.component("solicitacao-exames", () =>
	import("./components/SolicitacaoExames.vue")
)
// -----------------------------

const app = new Vue({
	el: "#app",
})
