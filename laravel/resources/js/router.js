// Ficheiro: resources/js/router.js

import Vue from "vue"
import VueRouter from "vue-router"

// (1) Importamos o nosso Layout principal
import DashboardLayout from "./views/DashboardLayout.vue"

// (2) Importamos as nossas "páginas"
import DashboardHome from "./views/DashboardHome.vue"
import GestaoExames from "./views/GestaoExames.vue"
import GestaoPacotes from "./views/GestaoPacotes.vue"
import SolicitacaoExames from "./views/SolicitacaoExames.vue"

Vue.use(VueRouter)

const routes = [
	// (3) Criamos UMA rota "pai" que usa o Layout
	{
		path: "/",
		component: DashboardLayout, // O Layout é o molde

		// (4) Todas as nossas páginas são "filhas" (children) deste layout
		// Elas serão injetadas na <router-view> do DashboardLayout
		children: [
			{
				path: "", // Rota raiz (ex: localhost/)
				name: "dashboard",
				component: DashboardHome,
			},
			{
				path: "exames", // ex: localhost/exames
				name: "gestao-exames",
				component: GestaoExames,
			},
			{
				path: "pacotes", // ex: localhost/pacotes
				name: "gestao-pacotes",
				component: GestaoPacotes,
			},
			{
				path: "solicitacao", // ex: localhost/solicitacao
				name: "solicitacao",
				component: SolicitacaoExames,
			},
		],
	},

	// Redirecionamento (se o layout falhar)
	{
		path: "*",
		redirect: "/",
	},
]

const router = new VueRouter({
	mode: "history",
	base: "/",
	routes: routes,
})

export default router
