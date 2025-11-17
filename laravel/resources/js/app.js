// Ficheiro: resources/js/app.js
import "../css/app.css"
import Vue from "vue"
import router from "./router"

// --- ADICIONE ESTE NOVO COMPONENTE ---
Vue.component("modal-gestao-exame", () =>
	import("./components/ModalGestaoExame.vue")
)
// ------------------------------------

const app = new Vue({
	el: "#app",
	router,
})
