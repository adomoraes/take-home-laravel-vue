<template>
	<div class="min-h-screen bg-light-bg">
		<Sidebar :isRetracted="isSidebarRetracted" @toggle="toggleSidebar" />

		<main
			class="transition-all duration-300 ease-in-out"
			:class="isSidebarRetracted ? 'ml-20' : 'ml-64'">
			<div class="p-4 md:p-8">
				<router-view />
			</div>
		</main>
	</div>
</template>

<script>
// Importamos o Sidebar localmente
import Sidebar from "../components/Sidebar.vue"

export default {
	name: "DashboardLayout",
	components: {
		Sidebar,
	},
	data() {
		return {
			// O estado "pai" que controla se a sidebar está retraída
			isSidebarRetracted: false,
		}
	},
	methods: {
		// O método que é chamado pelo evento '@toggle' do Sidebar
		toggleSidebar() {
			this.isSidebarRetracted = !this.isSidebarRetracted
		},
	},
}
</script>
