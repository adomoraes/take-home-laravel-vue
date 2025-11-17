<template>
	<div>
		<div
			v-if="notificacao.show"
			class="mb-4 p-4 rounded-md text-white"
			:class="notificacao.type === 'success' ? 'bg-green-500' : 'bg-red-500'">
			{{ notificacao.message }}
		</div>

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
					<tr v-if="exames.length === 0">
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
								class="text-blue-600 hover:text-blue-900 font-medium mr-3">
								Editar
							</button>
							<button
								@click="handleDelete(exame.id)"
								class="text-red-600 hover:text-red-900 font-medium">
								Excluir
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
	</div>
</template>

<script>
import api from "../apiService"

export default {
	name: "GestaoExames",

	data() {
		return {
			exames: [], // A lista de exames da API
			isModalOpen: false,
			exameSelecionado: null, // Guarda o exame a ser editado
			notificacao: {
				show: false,
				message: "",
				type: "success", // 'success' ou 'error'
			},
		}
	},

	methods: {
		// (Requisito) Notificação na tela
		mostrarNotificacao(message, type = "success") {
			this.notificacao.message = message
			this.notificacao.type = type
			this.notificacao.show = true

			// Esconde a notificação após 3 segundos
			setTimeout(() => {
				this.notificacao.show = false
			}, 3000)
		},

		// Carrega os dados da API
		fetchExames() {
			api
				.getExames()
				.then((response) => {
					this.exames = response.data
				})
				.catch((error) => {
					console.error("Erro ao carregar exames:", error)
					this.mostrarNotificacao("Erro ao carregar exames.", "error")
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
			this.mostrarNotificacao(mensagem, "success") // Mostra feedback
		},

		// Chamado pelo botão 'Excluir'
		handleDelete(id) {
			// (Requisito UX) Pede confirmação
			if (
				!window.confirm(
					"Tem certeza que deseja excluir este exame? Esta ação não pode ser desfeita."
				)
			) {
				return
			}

			api
				.deleteExame(id)
				.then(() => {
					this.fetchExames() // Atualiza a tabela
					this.mostrarNotificacao("Exame excluído com sucesso!", "success")
				})
				.catch((error) => {
					console.error("Erro ao excluir exame:", error)
					this.mostrarNotificacao("Erro ao excluir o exame.", "error")
				})
		},
	},

	// 'mounted' é chamado quando a página é carregada
	mounted() {
		this.fetchExames()
	},
}
</script>
