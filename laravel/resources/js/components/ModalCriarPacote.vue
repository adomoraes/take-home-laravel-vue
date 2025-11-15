<template>
	<div
		v-if="show"
		class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
		<div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
			<div class="flex justify-between items-center p-4 border-b">
				<h3 class="text-lg font-semibold">Novo Pacote de Exames</h3>
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
							for="pacote-name">
							Nome do Pacote
						</label>
						<input
							v-model="novoPacote.name"
							id="pacote-name"
							type="text"
							placeholder="Ex: Pacote Glaucoma"
							class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
							required />
						<p v-if="erros.name" class="text-red-500 text-xs italic">
							{{ erros.name[0] }}
						</p>
					</div>

					<div class="mb-4">
						<label
							class="block text-gray-700 text-sm font-bold mb-2"
							for="pacote-obs">
							Observações (Opcional)
						</label>
						<textarea
							v-model="novoPacote.observations"
							id="pacote-obs"
							placeholder="Orientações referentes ao pacote"
							class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
							rows="3"></textarea>
					</div>

					<div class="mb-4">
						<label class="block text-gray-700 text-sm font-bold mb-2">
							Selecione os Exames
						</label>
						<div class="border rounded p-2 h-64 overflow-y-auto bg-gray-50">
							<div
								v-for="exame in examesDisponiveis"
								:key="exame.id"
								class="flex items-center p-1">
								<input
									type="checkbox"
									:id="'exame-' + exame.id"
									:value="exame.id"
									v-model="examesSelecionadosIds"
									class="mr-2 h-4 w-4" />
								<label :for="'exame-' + exame.id" class="text-gray-700"
									>{{ exame.name }} ({{ exame.group }})</label
								>
							</div>
						</div>
						<p v-if="erros.exams" class="text-red-500 text-xs italic">
							{{ erros.exams[0] }}
						</p>
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
							Salvar Pacote
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
	name: "ModalCriarPacote",

	props: {
		// Controla a visibilidade (enviado pelo "pai")
		show: {
			type: Boolean,
			default: false,
		},
		// Lista de exames (enviada pelo "pai")
		examesDisponiveis: {
			type: Array,
			default: () => [],
		},
	},

	data() {
		return {
			// Estado interno do formulário
			novoPacote: {
				name: "",
				observations: "",
			},
			examesSelecionadosIds: [], // Array de IDs (ex: [1, 5, 12])
			erros: {}, // Para guardar erros de validação da API
		}
	},

	methods: {
		fechar() {
			// 1. Limpa o formulário
			this.novoPacote.name = ""
			this.novoPacote.observations = ""
			this.examesSelecionadosIds = []
			this.erros = {}

			// 2. Avisa o "pai" para fechar
			this.$emit("close")
		},

		salvar() {
			// Limpa erros antigos
			this.erros = {}

			// Validação local simples (a API fará a validação principal)
			if (this.examesSelecionadosIds.length === 0) {
				this.erros = { exams: ["Selecione pelo menos um exame."] }
				return
			}

			// 1. Monta o objeto final para a API
			const payload = {
				name: this.novoPacote.name,
				observations: this.novoPacote.observations,
				exams: this.examesSelecionadosIds,
			}

			// 2. Chama a API
			api
				.createPacote(payload)
				.then((response) => {
					// 3. Sucesso!
					console.log("Pacote criado:", response.data)

					// 4. Avisa o "pai" que um novo pacote foi criado
					this.$emit("pacote-criado")

					// 5. Fecha o modal
					this.fechar()
				})
				.catch((error) => {
					// 6. Lidar com erros (ex: erros de validação 422 da API)
					if (error.response && error.response.status === 422) {
						console.error("Erros de validação:", error.response.data.errors)
						// Armazena os erros para mostrar no formulário
						this.erros = error.response.data.errors
					} else {
						console.error("Erro ao salvar pacote:", error)
					}
				})
		},
	},
}
</script>
