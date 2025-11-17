<template>
	<div>
		<div class="flex justify-between items-center mb-6">
			<h1 class="text-3xl font-bold text-dark">Gestão de Pacotes</h1>
			<button
				@click="abrirModalParaCriar"
				class="bg-primary hover:bg-dark-accent text-white font-bold py-2 px-4 rounded-lg">
				Cadastrar Novo Pacote
			</button>
		</div>

		<div class="bg-white rounded-lg shadow-lg overflow-hidden">
			<table class="w-full min-w-full">
				<thead class="bg-gray-100">
					<tr>
						<th class="p-4 text-left text-sm font-semibold text-dark">Nome</th>
						<th class="p-4 text-left text-sm font-semibold text-dark">
							Nº de Exames
						</th>
						<th class="p-4 text-left text-sm font-semibold text-dark">Ações</th>
					</tr>
				</thead>
				<tbody>
					<tr v-if="isLoading">
						<td colspan="3" class="p-4 text-center text-gray-500">
							A carregar pacotes...
						</td>
					</tr>
					<tr v-else-if="pacotes.length === 0">
						<td colspan="3" class="p-4 text-center text-gray-500">
							Nenhum pacote cadastrado.
						</td>
					</tr>
					<tr
						v-for="pacote in pacotes"
						:key="pacote.id"
						class="border-b border-gray-200">
						<td class="p-4">{{ pacote.name }}</td>
						<td class="p-4">{{ pacote.exames.length }}</td>
						<td class="p-4">
							<button
								@click="abrirModalParaEditar(pacote)"
								class="text-blue-600 hover:text-blue-900 font-medium mr-3">
								Editar
							</button>
							<button
								@click="handleDelete(pacote.id)"
								class="text-red-600 hover:text-red-900 font-medium">
								Excluir
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<modal-gestao-pacote
			:show="isModalOpen"
			:pacote="pacoteSelecionado"
			:exames-disponiveis="todosExames"
			@close="fecharModal"
			@salvo="handleSalvo" />

		<modal-confirmacao
			:show="isConfirmOpen"
			titulo="Confirmar Exclusão"
			mensagem="Tem certeza que deseja excluir este pacote? Os exames dentro dele NÃO serão excluídos."
			@close="fecharModalConfirm"
			@confirm="confirmarExclusao" />
	</div>
</template>

<script>
import api from "../apiService"

export default {
	name: "GestaoPacotes",

	data() {
		return {
			isLoading: true,
			pacotes: [], // A lista de pacotes da API
			todosExames: [], // Lista de exames para passar ao modal
			isModalOpen: false,
			pacoteSelecionado: null,

			isConfirmOpen: false,
			pacoteParaExcluir: null,
		}
	},

	methods: {
		// Carrega os dados da API
		fetchPacotes() {
			this.isLoading = true
			api
				.getPacotes() // getPacotes já traz os exames (Eager Loading)
				.then((response) => {
					this.pacotes = response.data
				})
				.catch((error) => {
					console.error("Erro ao carregar pacotes:", error)
					this.$toast.error("Erro ao carregar pacotes.")
				})
				.finally(() => {
					this.isLoading = false
				})
		},
		// Precisamos da lista de exames para o modal
		fetchExames() {
			api
				.getExames()
				.then((response) => {
					this.todosExames = response.data
				})
				.catch((error) => {
					console.error("Erro ao carregar exames:", error)
					this.$toast.error("Erro ao carregar a lista de exames.")
				})
		},

		// Controla o modal
		abrirModalParaCriar() {
			this.pacoteSelecionado = null // Modo "Criar"
			this.isModalOpen = true
		},
		abrirModalParaEditar(pacote) {
			this.pacoteSelecionado = { ...pacote } // Modo "Editar"
			this.isModalOpen = true
		},
		fecharModal() {
			this.isModalOpen = false
			this.pacoteSelecionado = null
		},

		// Chamado quando o modal emite '@salvo'
		handleSalvo(mensagem) {
			this.fecharModal()
			this.fetchPacotes() // Atualiza a tabela
			this.$toast.success(mensagem) // Mostra feedback
		},

		// Lógica de Exclusão
		handleDelete(id) {
			this.pacoteParaExcluir = id
			this.isConfirmOpen = true
		},
		fecharModalConfirm() {
			this.isConfirmOpen = false
			this.pacoteParaExcluir = null
		},
		confirmarExclusao() {
			api
				.deletePacote(this.pacoteParaExcluir)
				.then(() => {
					this.fetchPacotes()
					this.$toast.success("Pacote excluído com sucesso!")
				})
				.catch((error) => {
					console.error("Erro ao excluir pacote:", error)
					this.$toast.error("Erro ao excluir o pacote.")
				})
				.finally(() => {
					this.fecharModalConfirm()
				})
		},
	},

	mounted() {
		this.fetchPacotes()
		this.fetchExames() // Carrega os exames para o modal
	},
}
</script>
