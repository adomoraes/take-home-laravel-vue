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
				<span v-else>Imprimir ({{ totalSelecionado }})</span>
			</button>
		</div>

		<div class="hidden md:grid grid-cols-1 md:grid-cols-2 gap-6">
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
							Exames Avulsos ({{ examesDisponiveis.length }})
						</button>
						<button
							@click="currentTab = 'pacotes'"
							:class="[
								currentTab === 'pacotes'
									? 'border-primary text-primary'
									: 'border-transparent text-gray-500 hover:text-dark',
							]"
							class="py-2 px-1 border-b-2 font-medium">
							Pacotes ({{ pacotesDisponiveis.length }})
						</button>
					</nav>
				</div>

				<div class="h-96 overflow-y-auto">
					<div v-if="currentTab === 'exames'">
						<ul>
							<li
								v-for="exame in examesDisponiveis"
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
								v-for="pacote in pacotesDisponiveis"
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
					<h2 class="text-xl font-semibold text-dark">
						Sua Solicitação ({{ totalSelecionado }})
					</h2>
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
								v-for="exame in examesAvulsosSelecionados"
								:key="'avulso-' + exame.id"
								class="flex justify-between items-center p-2 bg-light-bg rounded">
								<span class="text-dark">{{ exame.name }}</span>
								<button
									@click="removerExameAvulso(exame)"
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
								v-for="pacote in pacotesSelecionados"
								:key="'pacote-sel-' + pacote.id"
								class="flex justify-between items-center p-2 bg-light-bg rounded">
								<span class="text-dark">{{ pacote.name }}</span>
								<button
									@click="removerPacote(pacote)"
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

		<div class="block md:hidden">
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
							Exames ({{ examesAvulsosSelecionados.length }}/{{
								examesDaApi.length
							}})
						</button>
						<button
							@click="currentTab = 'pacotes'"
							:class="[
								currentTab === 'pacotes'
									? 'border-primary text-primary'
									: 'border-transparent text-gray-500 hover:text-dark',
							]"
							class="py-2 px-1 border-b-2 font-medium">
							Pacotes ({{ pacotesSelecionados.length }}/{{
								pacotesDaApi.length
							}})
						</button>
					</nav>
				</div>

				<div class="max-h-[60vh] overflow-y-auto">
					<div v-if="currentTab === 'exames'">
						<ul>
							<li
								v-for="exame in examesDaApi"
								:key="'mobile-exame-' + exame.id"
								class="flex justify-between items-center p-3 border-b"
								:class="{ 'bg-light-bg': isExameAvulsoSelected(exame) }">
								<div>
									<div class="font-medium text-dark">{{ exame.name }}</div>
									<div class="text-sm text-gray-600">{{ exame.group }}</div>
								</div>

								<button
									v-if="!isExameAvulsoSelected(exame)"
									@click="adicionarExameAvulso(exame)"
									class="text-primary hover:text-dark-accent text-2xl font-bold"
									title="Adicionar">
									+
								</button>
								<button
									v-else
									@click="removerExameAvulso(exame)"
									class="text-green-500 hover:text-green-700 text-2xl font-bold"
									title="Remover">
									✓
								</button>
							</li>
						</ul>
					</div>

					<div v-if="currentTab === 'pacotes'">
						<ul>
							<li
								v-for="pacote in pacotesDaApi"
								:key="'mobile-pacote-' + pacote.id"
								class="flex justify-between items-center p-3 border-b"
								:class="{ 'bg-light-bg': isPacoteSelected(pacote) }">
								<div>
									<div class="font-medium text-dark">{{ pacote.name }}</div>
									<div class="text-sm text-gray-600">
										{{ pacote.exames.length }} exame(s)
									</div>
								</div>

								<button
									v-if="!isPacoteSelected(pacote)"
									@click="adicionarPacote(pacote)"
									class="text-primary hover:text-dark-accent text-2xl font-bold"
									title="Adicionar">
									+
								</button>
								<button
									v-else
									@click="removerPacote(pacote)"
									class="text-green-500 hover:text-green-700 text-2xl font-bold"
									title="Remover">
									✓
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

			examesAvulsosSelecionados: [], // Array de OBJETOS de exame
			pacotesSelecionados: [], // Array de OBJETOS de pacote

			currentTab: "exames", // Controla as abas
			isLoading: false,
		}
	},

	computed: {
		totalSelecionado() {
			return (
				this.examesAvulsosSelecionados.length + this.pacotesSelecionados.length
			)
		},

		// Listas de "Disponíveis" para o layout Desktop
		examesDisponiveis() {
			return this.examesDaApi.filter(
				(exame) => !this.isExameAvulsoSelected(exame)
			)
		},
		pacotesDisponiveis() {
			return this.pacotesDaApi.filter(
				(pacote) => !this.isPacoteSelected(pacote)
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

		// --- LÓGICA DE SELEÇÃO (ITENS ÚNICOS) ---

		// Funções 'helper' para o template saber se um item está selecionado
		isExameAvulsoSelected(exame) {
			return this.examesAvulsosSelecionados.some((e) => e.id === exame.id)
		},
		isPacoteSelected(pacote) {
			return this.pacotesSelecionados.some((p) => p.id === pacote.id)
		},

		// Adiciona se não existir
		adicionarExameAvulso(exame) {
			if (this.isExameAvulsoSelected(exame)) {
				this.$toast.info(`"${exame.name}" já foi adicionado.`)
				return
			}
			this.examesAvulsosSelecionados.push(exame)
			this.$toast.success(`"${exame.name}" adicionado.`)
		},
		adicionarPacote(pacote) {
			if (this.isPacoteSelected(pacote)) {
				this.$toast.info(`Pacote "${pacote.name}" já foi adicionado.`)
				return
			}
			this.pacotesSelecionados.push(pacote)
			this.$toast.success(`Pacote "${pacote.name}" adicionado.`)
		},

		// Remove por ID (funciona em ambos os layouts)
		removerExameAvulso(exame) {
			this.examesAvulsosSelecionados = this.examesAvulsosSelecionados.filter(
				(e) => e.id !== exame.id
			)
			this.$toast.error(`"${exame.name}" removido.`)
		},
		removerPacote(pacote) {
			this.pacotesSelecionados = this.pacotesSelecionados.filter(
				(p) => p.id !== pacote.id
			)
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
