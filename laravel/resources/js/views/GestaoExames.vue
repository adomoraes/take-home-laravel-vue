<template>
	<div>
		<div class="flex justify-between items-center mb-6">
			<h1 class="text-3xl font-bold text-dark">Gestão de Exames</h1>
			<button
				@click="abrirModalParaCriar"
				class="bg-primary hover:bg-dark-accent text-white font-bold py-2 px-4 rounded-lg">
				Cadastrar Novo Exame
			</button>
		</div>

		<div class="bg-white rounded-lg shadow-lg overflow-hidden">
			<table class="w-full min-w-full">
				<thead class="bg-gray-100">
					<tr>
						<th class="p-4 text-left text-sm font-semibold text-dark">Nome</th>
						<th class="p-4 text-left text-sm font-semibold text-dark">Grupo</th>
						<th class="p-4 text-left text-sm font-semibold text-dark">
							Lateralidade
						</th>
						<th class="p-4 text-left text-sm font-semibold text-dark">Ações</th>
					</tr>
				</thead>
				<tbody>
					<tr v-if="isLoading">
						<td colspan="4" class="p-4 text-center text-gray-500">
							A carregar exames...
						</td>
					</tr>
					<tr v-else-if="exames.length === 0">
						<td colspan="4" class="p-4 text-center text-gray-500">
							Nenhum exame cadastrado.
						</td>
					</tr>
					<tr
						v-for="exame in exames"
						:key="exame.id"
						class="border-b border-gray-200">
						<td class="p-4">{{ exame.name }}</td>
						<td class="p-4">{{ exame.group }}</td>
						<td class="p-4">{{ exame.laterality || "N/A" }}</td>
						<td class="p-4">
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
			isLoading: true, // Adicionado para feedback inicial
			exames: [], // A lista de exames da API
			isModalOpen: false,
			exameSelecionado: null, // Guarda o exame a ser editado

			// Estado para o modal de confirmação
			isConfirmOpen: false,
			exameParaExcluir: null, // Guarda o ID do exame a ser excluído
		}
	},

	methods: {
		// Carrega os dados da API
		fetchExames() {
			this.isLoading = true
			api
				.getExames()
				.then((response) => {
					this.exames = response.data
				})
				.catch((error) => {
					console.error("Erro ao carregar exames:", error)
					// (Requisito) Usa o toast para erros
					this.$toast.error("Erro ao carregar exames.")
				})
				.finally(() => {
					this.isLoading = false
				})
		},

		// (Requisito UX) Controla o modal
		abrirModalParaCriar() {
			this.exameSelecionado = null // Modo "Criar"
			this.isModalOpen = true
		},
		abrirModalParaEditar(exame) {
			// Passamos uma *cópia* do exame para o modal
			this.exameSelecionado = { ...exame } // Modo "Editar"
			this.isModalOpen = true
		},
		fecharModal() {
			this.isModalOpen = false
			this.exameSelecionado = null
		},

		// Chamado quando o modal emite '@salvo'
		handleSalvo(mensagem) {
			this.fecharModal() // Fecha o modal
			this.fetchExames() // Atualiza a tabela
			// (Requisito) Usa o toast para sucesso
			this.$toast.success(mensagem)
		},

		// Chamado pelo botão 'Excluir'
		handleDelete(id) {
			// (Requisito UX) Abre o modal de confirmação
			this.exameParaExcluir = id
			this.isConfirmOpen = true
		},

		// Métodos de controlo do modal de confirmação
		fecharModalConfirm() {
			this.isConfirmOpen = false
			this.exameParaExcluir = null
		},

		confirmarExclusao() {
			// A lógica de exclusão real agora vive aqui
			api
				.deleteExame(this.exameParaExcluir)
				.then(() => {
					this.fetchExames()
					// (Requisito) Usa o toast para sucesso
					this.$toast.success("Exame excluído com sucesso!")
				})
				.catch((error) => {
					console.error("Erro ao excluir exame:", error)
					// (Requisito) Usa o toast para erros
					this.$toast.error("Erro ao excluir o exame.")
				})
				.finally(() => {
					// Fecha o modal independentemente do resultado
					this.fecharModalConfirm()
				})
		},
	},

	// 'mounted' é chamado quando a página é carregada
	mounted() {
		this.fetchExames()
	},
}
</script>
