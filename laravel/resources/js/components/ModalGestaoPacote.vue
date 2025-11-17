<template>
	<div
		v-if="show"
		class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto p-4">
		<div
			class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col">
			<div class="flex justify-between items-center p-4 border-b flex-shrink-0">
				<h3 class="text-lg font-semibold">{{ tituloModal }}</h3>
				<button
					@click="fechar"
					class="text-gray-500 hover:text-gray-800 text-2xl font-bold">
					&times;
				</button>
			</div>

			<div class="overflow-y-auto p-4">
				<form @submit.prevent="salvar">
					<div
						v-if="erros.geral"
						class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
						{{ erros.geral }}
					</div>

					<div class="mb-4">
						<label
							class="block text-gray-700 text-sm font-bold mb-2"
							for="pacote-name-gestao">
							Nome do Pacote
						</label>
						<input
							v-model="formData.name"
							id="pacote-name-gestao"
							type="text"
							placeholder="Ex: Pacote Glaucoma"
							class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
							:class="{ 'border-red-500': erros.name }" />
						<p v-if="erros.name" class="text-red-500 text-xs italic">
							{{ erros.name[0] }}
						</p>
					</div>

					<div class="mb-4">
						<label
							class="block text-gray-700 text-sm font-bold mb-2"
							for="pacote-obs-gestao">
							Observações (Opcional)
						</label>
						<textarea
							v-model="formData.observations"
							id="pacote-obs-gestao"
							placeholder="Orientações referentes ao pacote"
							class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
							rows="3"></textarea>
					</div>

					<div class="mb-4">
						<label class="block text-gray-700 text-sm font-bold mb-2">
							Selecione os Exames ({{ examesSelecionadosIds.length }})
						</label>

						<input
							v-model="exameSearchQuery"
							type="text"
							placeholder="Pesquisar exames..."
							class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-2" />

						<div class="border rounded bg-gray-50 h-64 overflow-y-auto">
							<div
								v-if="filteredExames.length === 0"
								class="p-4 text-center text-gray-500">
								<span v-if="exameSearchQuery">Nenhum exame encontrado.</span>
								<span v-else>Nenhum exame disponível.</span>
							</div>

							<ul>
								<li
									v-for="exame in filteredExames"
									:key="exame.id"
									class="flex justify-between items-center p-3 border-b"
									:class="{ 'bg-light-bg': isExameSelected(exame) }">
									<div>
										<div class="font-medium text-dark">{{ exame.name }}</div>
										<div class="text-sm text-gray-600">{{ exame.group }}</div>
									</div>

									<button
										v-if="!isExameSelected(exame)"
										@click="toggleExame(exame)"
										type="button"
										class="text-primary hover:text-dark-accent text-2xl font-bold"
										title="Adicionar">
										+
									</button>
									<button
										v-else
										@click="toggleExame(exame)"
										type="button"
										class="text-green-500 hover:text-green-700 text-2xl font-bold"
										title="Remover">
										✓
									</button>
								</li>
							</ul>
						</div>
						<p v-if="erros.exams" class="text-red-500 text-xs italic">
							{{ erros.exams[0] }}
						</p>
					</div>
				</form>
			</div>
			<div
				class="flex justify-end p-4 border-t bg-gray-50 rounded-b-lg flex-shrink-0">
				<button
					@click="fechar"
					type="button"
					class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
					Cancelar
				</button>
				<button
					@click="salvar"
					type="button"
					class="bg-primary hover:bg-dark-accent text-white font-bold py-2 px-4 rounded">
					Salvar Pacote
				</button>
			</div>
		</div>
	</div>
</template>

<script>
import api from "../apiService"

export default {
	name: "ModalGestaoPacote",

	props: {
		show: { type: Boolean, default: false },
		pacote: { type: Object, default: null },
		examesDisponiveis: { type: Array, default: () => [] },
	},

	data() {
		return {
			formData: {
				name: "",
				observations: "",
			},
			examesSelecionadosIds: [],
			erros: {},
			exameSearchQuery: "",
		}
	},

	computed: {
		tituloModal() {
			return this.pacote ? "Editar Pacote" : "Cadastrar Novo Pacote"
		},

		filteredExames() {
			if (!this.exameSearchQuery) {
				return this.examesDisponiveis
			}
			const query = this.exameSearchQuery.toLowerCase()
			return this.examesDisponiveis.filter((exame) =>
				exame.name.toLowerCase().includes(query)
			)
		},

		isExameSelected() {
			const selectedIds = new Set(this.examesSelecionadosIds)
			return (exame) => selectedIds.has(exame.id)
		},
	},

	watch: {
		show(novoValor) {
			if (novoValor) {
				this.erros = {}
				this.exameSearchQuery = ""

				if (this.pacote) {
					// Modo EDIÇÃO
					this.formData.name = this.pacote.name
					this.formData.observations = this.pacote.observations
					this.examesSelecionadosIds = this.pacote.exames.map(
						(exame) => exame.id
					)
				} else {
					// Modo CRIAÇÃO
					this.limparFormulario()
				}
			}
		},
	},

	methods: {
		limparFormulario() {
			this.formData.name = ""
			this.formData.observations = ""
			this.examesSelecionadosIds = []
			this.exameSearchQuery = ""
		},

		fechar() {
			this.$emit("close")
		},

		// (UX) Método "Toggle" para adicionar/remover exames
		toggleExame(exame) {
			const index = this.examesSelecionadosIds.indexOf(exame.id)

			if (index > -1) {
				// Já existe, vamos remover
				this.examesSelecionadosIds.splice(index, 1)
				this.$toast.error(`"${exame.name}" removido do pacote.`)
			} else {
				// Não existe, vamos adicionar
				this.examesSelecionadosIds.push(exame.id)
				this.$toast.success(`"${exame.name}" adicionado ao pacote.`)

				// --- ESTA É A CORREÇÃO ---
				// Limpa o filtro para mostrar a lista completa novamente
				this.exameSearchQuery = ""
				// -------------------------
			}
		},

		validarFormulario() {
			this.erros = {}
			if (!this.formData.name) {
				this.erros.name = ["O campo nome é obrigatório."]
			}
			if (this.examesSelecionadosIds.length === 0) {
				this.erros.exams = ["Selecione pelo menos um exame."]
			}
			return Object.keys(this.erros).length === 0
		},

		salvar() {
			if (!this.validarFormulario()) {
				return
			}

			const payload = {
				name: this.formData.name,
				observations: this.formData.observations,
				exams: this.examesSelecionadosIds,
			}

			const apiCall = this.pacote
				? api.updatePacote(this.pacote.id, payload)
				: api.createPacote(payload)

			apiCall
				.then((response) => {
					const mensagem = this.pacote
						? "Pacote atualizado com sucesso!"
						: "Pacote criado com sucesso!"
					this.$emit("salvo", mensagem)
				})
				.catch((error) => {
					if (error.response && error.response.status === 422) {
						this.erros = error.response.data.errors
					} else {
						this.erros.geral = "Ocorreu um erro inesperado. Tente novamente."
						console.error("Erro ao salvar pacote:", error)
					}
				})
		},
	},
}
</script>
