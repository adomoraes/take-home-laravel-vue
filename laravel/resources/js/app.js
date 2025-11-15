// Ficheiro: resources/js/app.js
import "../css/app.css"
import Vue from "vue"

// --- ADICIONE ESTAS LINHAS ---
// 1. Crie a pasta /components
// 2. Registe o seu componente "pai"
Vue.component("solicitacao-exames", () =>
	import("./components/SolicitacaoExames.vue")
)
Vue.component("modal-criar-exame", () =>
	import("./components/ModalCriarExame.vue")
)
Vue.component("modal-criar-pacote", () =>
	import("./components/ModalCriarPacote.vue")
)
Vue.component("modal-selecionar-pacote", () =>
	import("./components/ModalSelecionarPacote.vue")
)
Vue.component("modal-selecionar-exame", () =>
	import("./components/ModalSelecionarExame.vue")
)
const app = new Vue({
	el: "#app",
})
