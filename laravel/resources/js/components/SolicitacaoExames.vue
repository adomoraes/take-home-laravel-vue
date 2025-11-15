<template>
	<div class="p-6 bg-gray-50 rounded-lg shadow-md max-w-4xl mx-auto">
		<div class="flex justify-between items-center border-b pb-4 mb-4">
			<h2 class="text-xl font-bold text-gray-700">Solicitação de Exames</h2>
			<div>
				<button
					class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
					Pacote de exames
				</button>
				<button
					class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
					Novo pacote de Exames
				</button>
			</div>
		</div>

		<div class="bg-gray-100 p-4 rounded-md">
			<h3 class="font-semibold text-gray-800 mb-2">Exames avulsos</h3>

			<div v-if="examesSelecionados.length === 0" class="text-gray-500">
				Nenhum exame selecionado.
			</div>

			<div class="flex justify-end mt-6">
				<button
					class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
					Imprimir Solicitação
				</button>
			</div>
		</div>
	</div>
</template>

<script>
// 1. Importar o nosso serviço de API
import api from "../apiService"

export default {
	name: "SolicitacaoExames",

	data() {
		return {
			// Estado do nosso componente
			examesDaApi: [], // Onde guardaremos os exames que vêm do GET /api/exames
			pacotesDaApi: [], // Onde guardaremos os pacotes do GET /api/pacotes

			examesSelecionados: [], // Lista de exames avulsos adicionados
			pacotesSelecionados: [], // Lista de pacotes adicionados
		}
	},

	methods: {
		// Funções para carregar dados da API
		carregarExames() {
			api
				.getExames()
				.then((response) => {
					this.examesDaApi = response.data
					console.log("Exames carregados:", this.examesDaApi)
				})
				.catch((error) => {
					console.error("Erro ao carregar exames:", error)
				})
		},

		carregarPacotes() {
			api
				.getPacotes()
				.then((response) => {
					this.pacotesDaApi = response.data
					console.log("Pacotes carregados:", this.pacotesDaApi)
				})
				.catch((error) => {
					console.error("Erro ao carregar pacotes:", error)
				})
		},

		// Funções para os botões (a implementar)
		abrirModalPacotes() {
			// Lógica para abrir o ModalSelecionarPacote
		},
		abrirModalNovoPacote() {
			// Lógica para abrir o ModalCriarPacote
		},
		imprimir() {
			// Lógica para chamar o api.gerarPdf
		},
	},

	// 'mounted' é chamado quando o componente é carregado pela primeira vez
	mounted() {
		console.log("Componente SolicitacaoExames montado.")
		// Carregar os dados da API assim que o componente for carregado
		this.carregarExames()
		this.carregarPacotes()
	},
}
</script>

<style scoped>
/* Podemos adicionar CSS específico do componente aqui, se necessário */
</style>
