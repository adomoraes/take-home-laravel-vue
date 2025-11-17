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
							Selecione os Exames
						</label>
						<div class="border rounded p-2 bg-gray-50">
							<div
								v-for="exame in examesDisponiveis"
								:key="exame.id"
								class="flex items-center p-1">
								<input
									type="checkbox"
									:id="'gestao-exame-' + exame.id"
									:value="exame.id"
									v-model="examesSelecionadosIds"
									class="mr-2 h-4 w-4" />
								<label
									:for="'gestao-exame-' + exame.id"
									class="text-gray-700"
									>{{ exame.name }}</label
								>
							</div>
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
		pacote: { type: Object, default: null }, // Se nulo = Criar, se objeto = Editar
		examesDisponiveis: { type: Array, default: () => [] }, // Lista de todos os exames
	},

	data() {
		return {
			formData: {
				name: "",
				observations: "",
			},
			examesSelecionadosIds: [], // Array de IDs (ex: [1, 5, 12])
			erros: {},
		}
	},

	computed: {
		tituloModal() {
			return this.pacote ? "Editar Pacote" : "Cadastrar Novo Pacote"
		},
	},

	watch: {
		// Observador para preencher o formulário quando o modal abrir
		show(novoValor) {
			if (novoValor) {
				// Modal está abrindo
				this.erros = {}
				if (this.pacote) {
					// Modo EDIÇÃO: Preenche o formulário com os dados do pacote
					this.formData.name = this.pacote.name
					this.formData.observations = this.pacote.observations
					// Preenche os checkboxes com os exames que já estão no pacote
					this.examesSelecionadosIds = this.pacote.exames.map(
						(exame) => exame.id
					)
				} else {
					// Modo CRIAÇÃO: Limpa o formulário
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
		},

		fechar() {
			this.$emit("close")
		},

		// Validação client-side
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
				? api.updatePacote(this.pacote.id, payload) // Modo Editar
				: api.createPacote(payload) // Modo Criar

			apiCall
				.then((response) => {
					const mensagem = this.pacote
						? "Pacote atualizado com sucesso!"
						: "Pacote criado com sucesso!"
					this.$emit("salvo", mensagem) // Avisa o "pai"
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
