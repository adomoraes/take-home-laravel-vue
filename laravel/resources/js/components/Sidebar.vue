<template>
	<aside
		class="fixed top-0 left-0 z-40 h-screen transition-all duration-300 ease-in-out"
		:class="isRetracted ? 'w-20' : 'w-64'">
		<div class="flex h-full flex-col overflow-y-auto bg-dark-accent p-4">
			<div class="mb-6 flex items-center justify-between">
				<span v-if="!isRetracted" class="text-2xl font-bold text-white">
					Eyecare
				</span>
				<button @click="toggle" class="text-light-bg hover:text-white p-2">
					<svg
						xmlns="http://www.w3.org/2000/svg"
						fill="none"
						viewBox="0 0 24 24"
						stroke-width="1.5"
						stroke="currentColor"
						class="h-6 w-6">
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
					</svg>
				</button>
			</div>

			<ul class="space-y-2">
				<router-link
					v-for="link in links"
					:key="link.name"
					:to="link.path"
					:exact="link.exact"
					v-slot="{ href, navigate, isActive }"
					custom>
					<li
						:class="[isActive ? 'bg-primary' : 'hover:bg-dark', 'rounded-lg']">
						<a
							:href="href"
							@click="navigate"
							class="flex items-center p-3 text-light-bg"
							:title="link.name">
							<span class="h-6 w-6" v-html="link.iconSvg"></span>

							<span v-if="!isRetracted" class="ml-3">{{ link.name }}</span>
						</a>
					</li>
				</router-link>
			</ul>
		</div>
	</aside>
</template>

<script>
// (3) REMOVEMOS TODOS OS IMPORTS DE '@heroicons/vue'

export default {
	name: "Sidebar",
	// (4) REMOVEMOS O 'components: { ... }'
	props: {
		isRetracted: {
			type: Boolean,
			default: false,
		},
	},
	data() {
		return {
			// (5) Adicionamos a propriedade 'iconSvg' com o código SVG
			links: [
				{
					name: "Dashboard",
					path: "/",
					iconSvg:
						'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>',
					exact: true,
				},
				{
					name: "Gestão de Exames",
					path: "/exames",
					iconSvg:
						'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>',
				},
				{
					name: "Gestão de Pacotes",
					path: "/pacotes",
					iconSvg:
						'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10.5 11.25h3M12 15h.008m-7.008 0h14.016m-5.25 3.75h.008m-3.75 0h.008m-3.75 0h.008m0 0h.008m-3.75 0h.008m-3.75 0h.008M12 3.75h.008m-3.75 0h.008m-3.75 0h.008m0 0h.008m-3.75 0h.008m-3.75 0h.008m-3.75 0H12m0 0h.008m-3.75 0h.008m-3.75 0h.008m0 0h.008m-3.75 0h.008m-3.75 0h.008" /></svg>',
				},
				{
					name: "Solicitar Exames",
					path: "/solicitacao",
					iconSvg:
						'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5 6H5.625a1.125 1.125 0 01-1.125-1.125V6.75A1.125 1.125 0 015.625 5.625h3.375c.621 0 1.125.504 1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 003.375-3.375V7.125A1.125 1.125 0 0118.375 6h.223a1.125 1.125 0 011.125 1.125v13.5A1.125 1.125 0 0118.375 21.75H5.625a1.125 1.125 0 01-1.125-1.125v-1.5c0-.621.504-1.125 1.125-1.125H8.25" /></svg>',
				},
			],
		}
	},
	methods: {
		toggle() {
			this.$emit("toggle")
		},
	},
}
</script>
