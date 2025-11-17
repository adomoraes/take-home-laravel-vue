<template>
	<div>
		<div
			class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
			<h1 class="text-3xl font-bold text-dark">Gestão de Exames</h1>

			<div class="w-full md:w-1/2 lg:w-1/3">
				<input
					v-model="searchQuery"
					type="text"
					placeholder="Pesquisar por nome ou grupo..."
					class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-primary" />
			</div>
			<p>-- Ou --</p>
			<button
				@click="abrirModalParaCriar"
				class="bg-primary hover:bg-dark-accent text-white font-bold py-2 px-4 rounded-lg w-full md:w-auto flex-shrink-0">
				Cadastrar Novo Exame
			</button>
		</div>

		<div class="bg-white rounded-lg shadow-lg overflow-hidden">
			<table class="w-full min-w-full hidden md:table">
				<thead class="bg-gray-100">
					<tr>
						<th class="p-4 text-left text-sm font-semibold text-dark">Nome</th>
						<th class="p-4 text-left text-sm font-semibold text-dark">Grupo</th>
						<th class="p-4 text-left text-sm font-semibold text-dark">
							Lateralidade
						</th>
						<th class="p-4 text-center text-sm font-semibold text-dark">
							Ações
						</th>
					</tr>
				</thead>
				<tbody class="divide-y divide-gray-200">
					<tr v-if="isLoading">
						<td colspan="4" class="p-4 text-center text-gray-500">
							A carregar exames...
						</td>
					</tr>
					<tr v-else-if="filteredExames.length === 0">
						<td colspan="4" class="p-4 text-center text-gray-500">
							<span v-if="searchQuery"
								>Nenhum exame encontrado para "{{ searchQuery }}".</span
							>
							<span v-else>Nenhum exame cadastrado.</span>
						</td>
					</tr>
					<tr v-for="exame in filteredExames" :key="exame.id">
						<td class="p-4 align-top">{{ exame.name }}</td>
						<td class="p-4 align-top">{{ exame.group }}</td>
						<td class="p-4 align-top">{{ exame.laterality || "N/A" }}</td>
						<td class="p-4 text-center align-top">
							<button
								@click="abrirModalParaEditar(exame)"
								class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-light-bg"
								title="Editar Exame">
								<svg
									xmlns="http://www.w3.org/2000/svg"
									fill="none"
									viewBox="0 0 24 24"
									stroke-width="1.5"
									stroke="currentColor"
									class="h-5 w-5">
									<path
										stroke-linecap="round"
										stroke-linejoin="round"
										d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
								</svg>
							</button>
							<button
								@click="handleDelete(exame.id)"
								class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100 ml-2"
								title="Excluir Exame">
								<svg
									xmlns="http://www.w3.org/2000/svg"
									fill="none"
									viewBox="0 0 24 24"
									stroke-width="1.5"
									stroke="currentColor"
									class="h-5 w-5">
									<path
										stroke-linecap="round"
										stroke-linejoin="round"
										d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12.54 0c-.27-0.091-.54-0.18-.81-0.27m-.81.27L3 5.79m0 0c0-0.407.328-0.735.735-0.735h18.53c.407 0 .735.328.735.735m-19.995 0l-1.17 1.17m1.17-1.17h19.995" />
								</svg>
							</button>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="block md:hidden divide-y divide-gray-200">
				<div v-if="isLoading" class="p-4 text-center text-gray-500">
					A carregar exames...
				</div>
				<div
					v-else-if="filteredExames.length === 0"
					class="p-4 text-center text-gray-500">
					<span v-if="searchQuery"
						>Nenhum exame encontrado para "{{ searchQuery }}".</span
					>
					<span v-else>Nenhum exame cadastrado.</span>
				</div>

				<div
					v-for="exame in filteredExames"
					:key="'mobile-' + exame.id"
					class="p-4">
					<div class="flex justify-between items-center mb-3">
						<div class="font-bold text-dark text-lg">{{ exame.name }}</div>
						<div class="flex-shrink-0">
							<button
								@click="abrirModalParaEditar(exame)"
								class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-light-bg"
								title="Editar Exame">
								<svg
									xmlns="http://www.w3.org/2000/svg"
									fill="none"
									viewBox="0 0 24 24"
									stroke-width="1.5"
									stroke="currentColor"
									class="h-5 w-5">
									<path
										stroke-linecap="round"
										stroke-linejoin="round"
										d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
								</svg>
							</button>
							<button
								@click="handleDelete(exame.id)"
								class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100 ml-2"
								title="Excluir Exame">
								<svg
									xmlns="http://www.w3.org/2000/svg"
									fill="none"
									viewBox="0 0 24 24"
									stroke-width="1.5"
									stroke="currentColor"
									class="h-5 w-5">
									<path
										stroke-linecap="round"
										stroke-linejoin="round"
										d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12.54 0c-.27-0.091-.54-0.18-.81-0.27m-.81.27L3 5.79m0 0c0-0.407.328-0.735.735-0.735h18.53c.407 0 .735.328.735.735m-19.995 0l-1.17 1.17m1.17-1.17h19.995" />
								</svg>
							</button>
						</div>
					</div>
					<div class="space-y-1 text-sm">
						<div class="flex">
							<strong class="w-24 flex-shrink-0 text-gray-500">Grupo:</strong>
							<span class="text-gray-800">{{ exame.group }}</span>
						</div>
						<div class="flex">
							<strong class="w-24 flex-shrink-0 text-gray-500"
								>Lateralidade:</strong
							>
							<span class="text-gray-800">{{ exame.laterality || "N/A" }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<modal-gestao-exame
			:show="isModalOpen"
			:exame="exameSelecionado"
			@close="fecharModal"
			@salvo="handleSalvo" />
		<modal-confirmacao
			:show="isConfirmOpen"
			titulo="Confirmar Exclusão"
			mensagem="Tem certeza que deseja excluir este exame? Esta ação não pode ser desfeita."
			@close="fecharModalConfirm"
			@confirm="confirmarExclusao" />
	</div>
</template>

<script>
import api from "../apiService"

export default {
	name: "GestaoExames",

	data() {
		return {
			isLoading: true,
			exames: [],
			isModalOpen: false,
			exameSelecionado: null,
			isConfirmOpen: false,
			exameParaExcluir: null,
			searchQuery: "",
		}
	},

	computed: {
		filteredExames() {
			if (!this.searchQuery) {
				return this.exames
			}

			const lowerQuery = this.searchQuery.toLowerCase()

			return this.exames.filter((exame) => {
				const nameMatch = exame.name.toLowerCase().includes(lowerQuery)
				const groupMatch = exame.group.toLowerCase().includes(lowerQuery)
				return nameMatch || groupMatch
			})
		},
	},

	methods: {
		fetchExames() {
			this.isLoading = true
			api
				.getExames()
				.then((response) => {
					this.exames = response.data
				})
				.catch((error) => {
					console.error("Erro ao carregar exames:", error)
					this.$toast.error("Erro ao carregar exames.")
				})
				.finally(() => {
					this.isLoading = false
				})
		},

		abrirModalParaCriar() {
			this.exameSelecionado = null
			this.isModalOpen = true
		},
		abrirModalParaEditar(exame) {
			this.exameSelecionado = { ...exame }
			this.isModalOpen = true
		},
		fecharModal() {
			this.isModalOpen = false
			this.exameSelecionado = null
		},

		handleSalvo(mensagem) {
			this.fecharModal()
			this.fetchExames()
			this.$toast.success(mensagem)
		},

		handleDelete(id) {
			this.exameParaExcluir = id
			this.isConfirmOpen = true
		},
		fecharModalConfirm() {
			this.isConfirmOpen = false
			this.exameParaExcluir = null
		},
		confirmarExclusao() {
			api
				.deleteExame(this.exameParaExcluir)
				.then(() => {
					this.fetchExames()
					this.$toast.success("Exame excluído com sucesso!")
				})
				.catch((error) => {
					console.error("Erro ao excluir exame:", error)
					this.$toast.error("Erro ao excluir o exame.")
				})
				.finally(() => {
					this.fecharModalConfirm()
				})
		},
	},

	mounted() {
		this.fetchExames()
	},
}
</script>
