<template>
	<div class="min-h-screen bg-light-bg">
		<Header />

		<Sidebar :isRetracted="isSidebarRetracted" @toggle="toggleSidebar" />

		<main
			class="transition-all duration-300 ease-in-out"
			:class="mainMarginClass">
			<div class="p-4 md:p-8">
				<router-view />
			</div>
		</main>
	</div>
</template>

<script>
// (4) Importar AMBOS os componentes
import Sidebar from "../components/Sidebar.vue"
import Header from "../components/Header.vue" // <-- ADICIONADO

export default {
	name: "DashboardLayout",
	components: {
		Sidebar,
		Header, // <-- ADICIONADO
	},
	data() {
		return {
			isSidebarRetracted: false,
		}
	},
	computed: {
		// (5) Lógica da margem movida para uma computed property
		mainMarginClass() {
			// Em Mobile: Adiciona padding-top para compensar o Header fixo
			// Em Desktop: Adiciona margin-left para compensar a Sidebar
			if (this.isSidebarRetracted) {
				return "pt-16 md:pt-0 md:ml-20" // Desktop Retraído
			}
			return "pt-16 md:pt-0 md:ml-64" // Desktop Normal
		},
	},
	methods: {
		toggleSidebar() {
			this.isSidebarRetracted = !this.isSidebarRetracted
		},
	},
}
</script>
