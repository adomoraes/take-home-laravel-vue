<template>
	<aside
		class="fixed top-0 left-0 z-40 h-screen transition-all duration-300 ease-in-out"
		:class="isRetracted ? 'w-20' : 'w-64'">
		<div class="flex h-full flex-col overflow-y-auto bg-dark-accent p-4">
			<div class="mb-6 flex items-center justify-between">
				<span v-if="!isRetracted" class="text-2xl font-bold text-white">
					Exames
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
					v-slot="{ href, navigate, isActive }"
					custom>
					<li
						:class="[isActive ? 'bg-primary' : 'hover:bg-dark', 'rounded-lg']">
						<a
							:href="href"
							@click="navigate"
							class="flex items-center p-3 text-light-bg">
							<span class="h-6 w-6">
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
										d="M9 12.75l3 3m0 0l3-3m-3 3v-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
								</svg>
							</span>
							<span v-if="!isRetracted" class="ml-3">{{ link.name }}</span>
						</a>
					</li>
				</router-link>
			</ul>
		</div>
	</aside>
</template>

<script>
export default {
	name: "Sidebar",
	props: {
		// O 'pai' (Layout) vai dizer-nos se estamos retraídos
		isRetracted: {
			type: Boolean,
			default: false,
		},
	},
	data() {
		return {
			// Lista de páginas do nosso dashboard
			links: [
				{ name: "Dashboard", path: "/" },
				{ name: "Gestão de Exames", path: "/exames" },
				{ name: "Gestão de Pacotes", path: "/pacotes" },
				{ name: "Solicitar Exames", path: "/solicitacao" },
			],
		}
	},
	methods: {
		// Quando o botão for clicado, avisamos o 'pai'
		toggle() {
			this.$emit("toggle")
		},
	},
}
</script>
