<template>
	<div class="p-6 bg-gray-50 rounded-lg shadow-md max-w-4xl mx-auto">
		<div class="flex justify-between items-center border-b pb-4 mb-4">
			<h2 class="text-xl font-bold text-gray-700">Solicitação de Exames</h2>
			<div>
				<button
					@click="abrirModalSelecionar"
					class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
					Pacote de exames
				</button>
				<button
					@click="abrirModalNovoPacote"
					class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
					Novo pacote de Exames
				</button>
			</div>
		</div>

		<modal-criar-pacote
			:show="isModalCriarVisible"
			:exames-disponiveis="examesDaApi"
			@close="fecharModalNovoPacote"
			@pacote-criado="recarregarPacotes">
		</modal-criar-pacote>

		<modal-selecionar-pacote
			:show="isModalSelecionarVisible"
			:pacotes-disponiveis="pacotesDaApi"
			@close="fecharModalSelecionar"
			@pacotes-adicionados="adicionarPacotes">
		</modal-selecionar-pacote>

		<div class="bg-gray-100 p-4 rounded-md mt-6">
			<div v-if="pacotesSelecionados.length > 0" class="mb-4">
				<div
					v-for="pacote in pacotesSelecionados"
					:key="pacote.id"
					class="mb-2 p-3 bg-white rounded shadow-sm border">
					<h3 class="font-semibold text-gray-800">{{ pacote.name }}</h3>
					<ul class="list-disc pl-5 mt-1 text-sm text-gray-600">
						<li v-for="exame in pacote.exames" :key="exame.id">
							{{ exame.name }}
						</li>
					</ul>
				</div>
			</div>

			<div v-if="pacotesSelecionados.length === 0" class="text-gray-500">
				Nenhum pacote selecionado.
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
import api from "../apiService"
// Os modais são carregados automaticamente porque os registámos no app.js

export default {
	name: "SolicitacaoExames",

	data() {
		return {
			examesDaApi: [],
			pacotesDaApi: [],
			examesSelecionados: [],
			pacotesSelecionados: [], // Array de OBJETOS de pacote

			// Estado para controlar a visibilidade do modal
			isModalCriarVisible: false,
			isModalSelecionarVisible: false, // <-- ADICIONADO
		}
	},

	methods: {
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

		// Métodos para controlar o modal de CRIAR
		abrirModalNovoPacote() {
			this.isModalCriarVisible = true
		},
		fecharModalNovoPacote() {
			this.isModalCriarVisible = false
		},
		recarregarPacotes() {
			this.fecharModalNovoPacote()
			this.carregarPacotes()
		},

		// --- MÉTODOS ADICIONADOS PARA O MODAL DE SELECIONAR ---
		abrirModalSelecionar() {
			this.isModalSelecionarVisible = true
		},
		fecharModalSelecionar() {
			this.isModalSelecionarVisible = false
		},
		adicionarPacotes(pacotes) {
			// 'pacotes' é o array de objetos vindo do evento '@pacotes-adicionados'

			// Evitar duplicados
			pacotes.forEach((pacote) => {
				if (!this.pacotesSelecionados.find((p) => p.id === pacote.id)) {
					this.pacotesSelecionados.push(pacote)
				}
			})

			this.fecharModalSelecionar()
			console.log("Pacotes na solicitação:", this.pacotesSelecionados)
		},
		// --- FIM DOS MÉTODOS ADICIONADOS ---

		imprimir() {
			// Lógica para impressão (futuro)
		},
	},

	// 'mounted' é chamado quando o componente é carregado
	mounted() {
		console.log("Componente SolicitacaoExames montado.")
		this.carregarExames()
		this.carregarPacotes()
	},
}
</script>
