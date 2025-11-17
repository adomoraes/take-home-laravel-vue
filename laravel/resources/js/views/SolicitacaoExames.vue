<template>
	<div class="p-6 bg-gray-50 rounded-lg shadow-md max-w-4xl mx-auto">
		<div class="flex justify-between items-center border-b pb-4 mb-4">
			<h2 class="text-xl font-bold text-gray-700">Solicitação de Exames</h2>

			<div class="flex flex-wrap gap-2">
				<button
					@click="abrirModalCriarExame"
					class="bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded">
					Cadastrar Novo Exame
				</button>
				<button
					@click="abrirModalExame"
					class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">
					Adicionar Exame Avulso
				</button>
				<button
					@click="abrirModalSelecionar"
					class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
					Pacote de exames
				</button>
				<button
					@click="abrirModalNovoPacote"
					class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
					Novo pacote de Exames
				</button>
			</div>
		</div>

		<modal-criar-exame
			:show="isModalCriarExameVisible"
			@close="fecharModalCriarExame"
			@exame-criado="handleExameCriado">
		</modal-criar-exame>

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

		<modal-selecionar-exame
			:show="isModalExameVisible"
			:exames-disponiveis="examesDaApi"
			@close="fecharModalExame"
			@exames-adicionados="adicionarExamesAvulsos">
		</modal-selecionar-exame>

		<div class="bg-gray-100 p-4 rounded-md mt-6">
			<div
				v-if="examesAvulsosSelecionados.length > 0"
				class="mb-4 p-3 bg-white rounded shadow-sm border">
				<h3 class="font-semibold text-gray-800">Exames avulsos</h3>
				<ul class="list-disc pl-5 mt-1 text-sm text-gray-600">
					<li v-for="exame in examesAvulsosSelecionados" :key="exame.id">
						{{ exame.name }} ({{ exame.comment }})
					</li>
				</ul>
			</div>

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

			<div
				v-if="
					pacotesSelecionados.length === 0 &&
					examesAvulsosSelecionados.length === 0
				"
				class="text-gray-500">
				Nenhum exame ou pacote selecionado.
			</div>

			<div class="flex justify-end mt-6">
				<button
					@click="imprimir"
					:disabled="
						isLoading ||
						(pacotesSelecionados.length === 0 &&
							examesAvulsosSelecionados.length === 0)
					"
					class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
					:class="{
						'opacity-50 cursor-not-allowed':
							isLoading ||
							(pacotesSelecionados.length === 0 &&
								examesAvulsosSelecionados.length === 0),
					}">
					<span v-if="isLoading">Aguarde...</span>
					<span v-else>Imprimir Solicitação</span>
				</button>
			</div>
			<p
				v-if="erroImpressao"
				class="text-red-500 text-xs italic text-right mt-2">
				{{ erroImpressao }}
			</p>
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

			examesAvulsosSelecionados: [], // Array de OBJETOS de exame
			pacotesSelecionados: [], // Array de OBJETOS de pacote

			isModalCriarVisible: false,
			isModalSelecionarVisible: false,
			isModalExameVisible: false,
			isModalCriarExameVisible: false, // <-- ESTADO ADICIONADO

			isLoading: false,
			erroImpressao: null,
		}
	},

	methods: {
		// --- MÉTODOS DE CARREGAMENTO ---
		carregarExames() {
			api
				.getExames()
				.then((response) => {
					this.examesDaApi = response.data
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
				})
				.catch((error) => {
					console.error("Erro ao carregar pacotes:", error)
				})
		},

		// --- MÉTODOS MODAL NOVO PACOTE ---
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

		// --- MÉTODOS MODAL SELECIONAR PACOTE ---
		abrirModalSelecionar() {
			this.isModalSelecionarVisible = true
		},
		fecharModalSelecionar() {
			this.isModalSelecionarVisible = false
		},
		adicionarPacotes(pacotes) {
			pacotes.forEach((pacote) => {
				if (!this.pacotesSelecionados.find((p) => p.id === pacote.id)) {
					this.pacotesSelecionados.push(pacote)
				}
			})
			this.fecharModalSelecionar()
		},

		// --- MÉTODOS MODAL EXAME AVULSO ---
		abrirModalExame() {
			this.isModalExameVisible = true
		},
		fecharModalExame() {
			this.isModalExameVisible = false
		},
		adicionarExamesAvulsos(exames) {
			exames.forEach((exame) => {
				if (!this.examesAvulsosSelecionados.find((e) => e.id === exame.id)) {
					this.examesAvulsosSelecionados.push(exame)
				}
			})
			this.fecharModalExame()
			console.log(
				"Exames avulsos na solicitação:",
				this.examesAvulsosSelecionados
			)
		},

		// --- MÉTODOS ADICIONADOS PARA O MODAL DE CRIAR EXAME ---
		abrirModalCriarExame() {
			this.isModalCriarExameVisible = true
		},
		fecharModalCriarExame() {
			this.isModalCriarExameVisible = false
		},
		handleExameCriado() {
			// Este método é chamado pelo evento @exame-criado
			this.fecharModalCriarExame()
			this.carregarExames() // Recarrega a lista de exames
		},
		// --- FIM DOS MÉTODOS ADICIONADOS ---

		// --- MÉTODO DE IMPRESSÃO ---
		imprimir() {
			this.isLoading = true
			this.erroImpressao = null

			const examesIds = this.examesAvulsosSelecionados.map((exame) => exame.id)
			const pacotesIds = this.pacotesSelecionados.map((pacote) => pacote.id)

			const payload = {
				exames: examesIds,
				pacotes: pacotesIds,
			}

			api
				.gerarPdf(payload)
				.then((response) => {
					const file = new Blob([response.data], { type: "application/pdf" })
					const fileURL = URL.createObjectURL(file)
					window.open(fileURL, "_blank")
					this.isLoading = false
				})
				.catch((error) => {
					console.error("Erro ao gerar PDF:", error)
					this.erroImpressao = "Não foi possível gerar o PDF. Tente novamente."
					this.isLoading = false
				})
		},
	},

	mounted() {
		console.log("Componente SolicitacaoExames montado.")
		this.carregarExames()
		this.carregarPacotes()
	},
}
</script>
