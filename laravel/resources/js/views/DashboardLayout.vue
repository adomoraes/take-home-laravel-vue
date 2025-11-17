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
import Sidebar from "../components/Sidebar.vue"
import Header from "../components/Header.vue"

export default {
	name: "DashboardLayout",
	components: {
		Sidebar,
		Header,
	},
	data() {
		return {
			isSidebarRetracted: false,
		}
	},
	computed: {
		mainMarginClass() {
			if (this.isSidebarRetracted) {
				return "pt-16 md:pt-0 md:ml-20"
			}
			return "pt-16 md:pt-0 md:ml-64"
		},
	},
	methods: {
		toggleSidebar() {
			this.isSidebarRetracted = !this.isSidebarRetracted
		},
	},
}
</script>
