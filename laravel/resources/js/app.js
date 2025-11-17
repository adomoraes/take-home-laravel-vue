// Ficheiro: resources/js/app.js
import "../css/app.css"
import Vue from "vue"
import router from "./router"

// --- ADICIONE ESTE NOVO COMPONENTE ---
Vue.component("modal-gestao-exame", () =>
	import("./components/ModalGestaoExame.vue")
)
// ------------------------------------
// --- ADICIONAR A CONFIGURAÇÃO DO TOAST ---
import Toast from "vue-toastification"
import "vue-toastification/dist/index.css" // Importa o CSS

Vue.use(Toast, {
	transition: "Vue-Toastification__fade",
	maxToasts: 5,
	newestOnTop: true,
})
// ------------------------------------
// --- ADICIONE ESTE NOVO COMPONENTE ---
Vue.component("modal-confirmacao", () =>
	import("./components/ModalConfirmacao.vue")
)
// ------------------------------------

const app = new Vue({
	el: "#app",
	router,
})
