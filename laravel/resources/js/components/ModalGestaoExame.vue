<template>
	<div
		v-if="show"
		class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
		<div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
			<div class="flex justify-between items-center p-4 border-b">
				<h3 class="text-lg font-semibold">{{ tituloModal }}</h3>
				<button
					@click="fechar"
					class="text-gray-500 hover:text-gray-800 text-2xl font-bold">
					&times;
				</button>
			</div>

			<div class="p-4">
				<form @submit.prevent="salvar">
					<div
						v-if="erros.geral"
						class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
						{{ erros.geral }}
					</div>

					<div class="mb-4">
						<label
							class="block text-gray-700 text-sm font-bold mb-2"
							for="exame-name-gestao">
							Nome do Exame
						</label>
						<input
							v-model="formData.name"
							id="exame-name-gestao"
							type="text"
							class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
							:class="{ 'border-red-500': erros.name }" />
						<p v-if="erros.name" class="text-red-500 text-xs italic">
							{{ erros.name[0] }}
						</p>
					</div>

					<div class="mb-4">
						<label
							class="block text-gray-700 text-sm font-bold mb-2"
							for="exame-comment-gestao">
							Comentário / Observação
						</label>
						<textarea
							v-model="formData.comment"
							id="exame-comment-gestao"
							class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
							:class="{ 'border-red-500': erros.comment }"
							rows="3"></textarea>
						<p v-if="erros.comment" class="text-red-500 text-xs italic">
							{{ erros.comment[0] }}
						</p>
					</div>

					<div class="flex gap-4 mb-4">
						<div class="w-1/2">
							<label
								class="block text-gray-700 text-sm font-bold mb-2"
								for="exame-group-gestao">
								Grupo de Impressão
							</label>
							<select
								v-model="formData.group"
								id="exame-group-gestao"
								class="shadow border rounded w-full py-2 px-3 text-gray-700"
								:class="{ 'border-red-500': erros.group }">
								<option disabled value="">Selecione...</option>
								<option
									v-for="grupo in gruposPermitidos"
									:key="grupo"
									:value="grupo">
									{{ grupo }}
								</option>
							</select>
							<p v-if="erros.group" class="text-red-500 text-xs italic">
								{{ erros.group[0] }}
							</p>
						</div>

						<div class="w-1/2">
							<label
								class="block text-gray-700 text-sm font-bold mb-2"
								for="exame-laterality-gestao">
								Lateralidade (Opcional)
							</label>
							<select
								v-model="formData.laterality"
								id="exame-laterality-gestao"
								class="shadow border rounded w-full py-2 px-3 text-gray-700">
								<option value="">N/A</option>
								<option
									v-for="lat in lateralidadesPermitidas"
									:key="lat"
									:value="lat">
									{{ lat }}
								</option>
							</select>
						</div>
					</div>

					<div class="flex justify-end pt-4 border-t">
						<button
							@click="fechar"
							type="button"
							class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
							Cancelar
						</button>
						<button
							type="submit"
							class="bg-primary hover:bg-dark-accent text-white font-bold py-2 px-4 rounded">
							Salvar
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</template>

<script>
import api from "../apiService"

export default {
	name: "ModalGestaoExame",

	props: {
		show: { type: Boolean, default: false },
		exame: { type: Object, default: null },
	},

	data() {
		return {
			formData: {
				name: "",
				comment: "",
				group: "",
				laterality: "",
			},
			erros: {},
			gruposPermitidos: [
				"Individual",
				"Grupo 1",
				"Grupo 2",
				"Grupo 3",
				"Grupo 4",
				"Grupo 5",
			],
			lateralidadesPermitidas: ["OD", "OE", "AO"],
		}
	},

	computed: {
		tituloModal() {
			return this.exame ? "Editar Exame" : "Cadastrar Novo Exame"
		},
	},

	watch: {
		show(novoValor) {
			if (novoValor) {
				this.erros = {}
				if (this.exame) {
					this.formData = { ...this.exame }
				} else {
					this.limparFormulario()
				}
			}
		},
	},

	methods: {
		limparFormulario() {
			this.formData = {
				name: "",
				comment: "",
				group: "",
				laterality: "",
			}
		},

		fechar() {
			this.$emit("close")
		},

		validarFormulario() {
			this.erros = {}

			if (!this.formData.name) {
				this.erros.name = ["O campo nome é obrigatório."]
			}
			if (!this.formData.comment) {
				this.erros.comment = ["O campo comentário é obrigatório."]
			}
			if (!this.formData.group) {
				this.erros.group = ["O campo grupo é obrigatório."]
			}

			return Object.keys(this.erros).length === 0
		},

		salvar() {
			if (!this.validarFormulario()) {
				return
			}

			let payload = { ...this.formData }
			if (!payload.laterality) {
				delete payload.laterality
			}

			const promise = this.exame
				? api.updateExame(this.exame.id, payload)
				: api.createExame(payload)

			promise
				.then(() => {
					const mensagem = this.exame
						? "Exame atualizado com sucesso!"
						: "Exame criado com sucesso!"
					this.$emit("salvo", mensagem)
				})
				.catch((error) => {
					if (error.response && error.response.status === 422) {
						this.erros = error.response.data.errors
					} else {
						this.erros.geral = "Ocorreu um erro inesperado. Tente novamente."
						console.error("Erro ao salvar exame:", error)
					}
				})
		},
	},
}
</script>
