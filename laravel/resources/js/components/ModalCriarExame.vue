<template>
	<div
		v-if="show"
		class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
		<div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
			<div class="flex justify-between items-center p-4 border-b">
				<h3 class="text-lg font-semibold">Cadastrar Novo Exame</h3>
				<button
					@click="fechar"
					class="text-gray-500 hover:text-gray-800 text-2xl font-bold">
					&times;
				</button>
			</div>

			<div class="p-4">
				<form @submit.prevent="salvar">
					<div class="mb-4">
						<label
							class="block text-gray-700 text-sm font-bold mb-2"
							for="exame-name">
							Nome do Exame
						</label>
						<input
							v-model="novoExame.name"
							id="exame-name"
							type="text"
							class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
							required />
						<p v-if="erros.name" class="text-red-500 text-xs italic">
							{{ erros.name[0] }}
						</p>
					</div>

					<div class="mb-4">
						<label
							class="block text-gray-700 text-sm font-bold mb-2"
							for="exame-comment">
							Comentário / Observação
						</label>
						<textarea
							v-model="novoExame.comment"
							id="exame-comment"
							class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
							rows="3"
							required></textarea>
						<p v-if="erros.comment" class="text-red-500 text-xs italic">
							{{ erros.comment[0] }}
						</p>
					</div>

					<div class="flex gap-4 mb-4">
						<div class="w-1/2">
							<label
								class="block text-gray-700 text-sm font-bold mb-2"
								for="exame-group">
								Grupo de Impressão
							</label>
							<select
								v-model="novoExame.group"
								id="exame-group"
								class="shadow border rounded w-full py-2 px-3 text-gray-700"
								required>
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
								for="exame-laterality">
								Lateralidade (Opcional)
							</label>
							<select
								v-model="novoExame.laterality"
								id="exame-laterality"
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
							class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
							Salvar Exame
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
	name: "ModalCriarExame",

	props: {
		show: {
			type: Boolean,
			default: false,
		},
	},

	data() {
		return {
			novoExame: {
				name: "",
				comment: "",
				group: "",
				laterality: "",
			},
			// Dados baseados no README
			gruposPermitidos: [
				"Individual",
				"Grupo 1",
				"Grupo 2",
				"Grupo 3",
				"Grupo 4",
				"Grupo 5",
			],
			lateralidadesPermitidas: ["OD", "OE", "AO"],

			erros: {}, // Para guardar erros de validação da API
		}
	},

	methods: {
		limparFormulario() {
			this.novoExame.name = ""
			this.novoExame.comment = ""
			this.novoExame.group = ""
			this.novoExame.laterality = ""
			this.erros = {}
		},

		fechar() {
			this.limparFormulario()
			this.$emit("close")
		},

		salvar() {
			this.erros = {}

			// Prepara o payload (remove 'laterality' se estiver vazio)
			let payload = { ...this.novoExame }
			if (!payload.laterality) {
				delete payload.laterality
			}

			api
				.createExame(payload)
				.then((response) => {
					console.log("Exame criado:", response.data)
					this.$emit("exame-criado") // Avisa o "pai"
					this.fechar()
				})
				.catch((error) => {
					if (error.response && error.response.status === 422) {
						console.error("Erros de validação:", error.response.data.errors)
						this.erros = error.response.data.errors
					} else {
						console.error("Erro ao salvar exame:", error)
					}
				})
		},
	},
}
</script>
