<template>
	<div>
		<div class="flex justify-between items-center mb-6">
			<h1 class="text-3xl font-bold text-dark">Solicitar Exames</h1>
			<button
				@click="imprimir"
				:disabled="isLoading || totalSelecionado === 0"
				class="bg-primary hover:bg-dark-accent text-white font-bold py-2 px-4 rounded-lg"
				:class="{
					'opacity-50 cursor-not-allowed': isLoading || totalSelecionado === 0,
				}">
				<span v-if="isLoading">Aguarde...</span>
				<span v-else>Imprimir Solicitação ({{ totalSelecionado }})</span>
			</button>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
			<div class="bg-white rounded-lg shadow-lg">
				<div class="p-4 border-b">
					<nav class="flex space-x-4">
						<button
							@click="currentTab = 'exames'"
							:class="[
								currentTab === 'exames'
									? 'border-primary text-primary'
									: 'border-transparent text-gray-500 hover:text-dark',
							]"
							class="py-2 px-1 border-b-2 font-medium">
							Exames Avulsos ({{ examesDaApi.length }})
						</button>
						<button
							@click="currentTab = 'pacotes'"
							:class="[
								currentTab === 'pacotes'
									? 'border-primary text-primary'
									: 'border-transparent text-gray-500 hover:text-dark',
							]"
							class="py-2 px-1 border-b-2 font-medium">
							Pacotes ({{ pacotesDaApi.length }})
						</button>
					</nav>
				</div>

				<div class="h-96 overflow-y-auto">
					<div v-if="currentTab === 'exames'">
						<ul>
							<li
								v-for="exame in examesDaApi"
								:key="'exame-' + exame.id"
								class="flex justify-between items-center p-3 hover:bg-light-bg border-b">
								<div>
									<div class="font-medium text-dark">{{ exame.name }}</div>
									<div class="text-sm text-gray-600">{{ exame.group }}</div>
								</div>
								<button
									@click="adicionarExameAvulso(exame)"
									class="text-primary hover:text-dark-accent text-2xl font-bold"
									title="Adicionar">
									+
								</button>
							</li>
						</ul>
					</div>

					<div v-if="currentTab === 'pacotes'">
						<ul>
							<li
								v-for="pacote in pacotesDaApi"
								:key="'pacote-' + pacote.id"
								class="flex justify-between items-center p-3 hover:bg-light-bg border-b">
								<div>
									<div class="font-medium text-dark">{{ pacote.name }}</div>
									<div class="text-sm text-gray-600">
										{{ pacote.exames.length }} exame(s)
									</div>
								</div>
								<button
									@click="adicionarPacote(pacote)"
									class="text-primary hover:text-dark-accent text-2xl font-bold"
									title="Adicionar">
									+
								</button>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="bg-white rounded-lg shadow-lg">
				<div class="p-4 border-b">
					<h2 class="text-xl font-semibold text-dark">Sua Solicitação</h2>
				</div>

				<div class="h-96 overflow-y-auto p-4">
					<div
						v-if="totalSelecionado === 0"
						class="text-center text-gray-500 pt-16">
						<p>Selecione exames ou pacotes da lista ao lado.</p>
					</div>

					<div v-if="examesAvulsosSelecionados.length > 0" class="mb-4">
						<h3 class="font-semibold text-gray-800 mb-2">Exames avulsos</h3>
						<ul class="list-none space-y-2">
							<li
								v-for="(exame, index) in examesAvulsosSelecionados"
								:key="'avulso-' + index + exame.id"
								class="flex justify-between items-center p-2 bg-light-bg rounded">
								<span class="text-dark">{{ exame.name }}</span>
								<button
									@click="removerExame(index)"
									class="text-red-500 hover:text-red-700 text-xl"
									title="Remover">
									&times;
								</button>
							</li>
						</ul>
					</div>

					<div v-if="pacotesSelecionados.length > 0">
						<h3 class="font-semibold text-gray-800 mb-2">Pacotes</h3>
						<ul class="list-none space-y-2">
							<li
								v-for="(pacote, index) in pacotesSelecionados"
								:key="'pacote-sel-' + index + pacote.id"
								class="flex justify-between items-center p-2 bg-light-bg rounded">
								<span class="text-dark">{{ pacote.name }}</span>
								<button
									@click="removerPacote(index)"
									class="text-red-500 hover:text-red-700 text-xl"
									title="Remover">
									&times;
								</button>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import api from "../apiService"

export default {
	name: "SolicitacaoExames",

	data() {
		return {
			examesDaApi: [],
			pacotesDaApi: [],

			// Coluna da Direita (A Solicitação)
			examesAvulsosSelecionados: [], // Array de OBJETOS de exame
			pacotesSelecionados: [], // Array de OBJETOS de pacote

			currentTab: "exames", // Controla as abas 'exames' ou 'pacotes'

			isLoading: false,
		}
	},

	computed: {
		// Calcula o total de itens na solicitação
		totalSelecionado() {
			return (
				this.examesAvulsosSelecionados.length + this.pacotesSelecionados.length
			)
		},
	},

	methods: {
		// --- MÉTODOS DE CARREGAMENTO (API) ---
		carregarExames() {
			api
				.getExames()
				.then((response) => {
					this.examesDaApi = response.data
				})
				.catch((error) => {
					this.$toast.error("Erro ao carregar exames.")
				})
		},
		carregarPacotes() {
			api
				.getPacotes()
				.then((response) => {
					this.pacotesDaApi = response.data
				})
				.catch((error) => {
					this.$toast.error("Erro ao carregar pacotes.")
				})
		},

		// --- MÉTODOS DE MANIPULAÇÃO DA LISTA (UX) ---
		adicionarExameAvulso(exame) {
			// (Requisito UX) Permite adicionar o mesmo exame avulso várias vezes
			this.examesAvulsosSelecionados.push(exame)
			this.$toast.success(`"${exame.name}" adicionado.`)
		},
		adicionarPacote(pacote) {
			// (Requisito UX) Só permite adicionar o mesmo pacote uma vez
			if (this.pacotesSelecionados.find((p) => p.id === pacote.id)) {
				this.$toast.info(`Pacote "${pacote.name}" já foi adicionado.`)
				return
			}
			this.pacotesSelecionados.push(pacote)
			this.$toast.success(`Pacote "${pacote.name}" adicionado.`)
		},

		// Remove pelo *índice* do array, para permitir duplicados (no caso dos exames)
		removerExame(index) {
			const exame = this.examesAvulsosSelecionados[index]
			this.examesAvulsosSelecionados.splice(index, 1)
			this.$toast.error(`"${exame.name}" removido.`)
		},
		removerPacote(index) {
			const pacote = this.pacotesSelecionados[index]
			this.pacotesSelecionados.splice(index, 1)
			this.$toast.error(`Pacote "${pacote.name}" removido.`)
		},

		// --- MÉTODO DE IMPRESSÃO (API) ---
		imprimir() {
			this.isLoading = true

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

					// Limpa a solicitação após a impressão
					this.examesAvulsosSelecionados = []
					this.pacotesSelecionados = []
					this.$toast.success("PDF gerado com sucesso!")
				})
				.catch((error) => {
					console.error("Erro ao gerar PDF:", error)
					this.$toast.error("Não foi possível gerar o PDF. Tente novamente.")
					this.isLoading = false
				})
		},
	},

	mounted() {
		this.carregarExames()
		this.carregarPacotes()
	},
}
</script>
