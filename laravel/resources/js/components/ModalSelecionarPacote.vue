<template>
	<div
		v-if="show"
		class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
		<div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
			<div class="flex justify-between items-center p-4 border-b">
				<h3 class="text-lg font-semibold">Selecionar Pacotes de Exames</h3>
				<button
					@click="fechar"
					class="text-gray-500 hover:text-gray-800 text-2xl font-bold">
					&times;
				</button>
			</div>

			<div class="p-4">
				<div class="border rounded p-2 h-64 overflow-y-auto bg-gray-50">
					<div v-if="pacotesDisponiveis.length === 0" class="text-gray-500 p-2">
						Nenhum pacote cadastrado. Você pode criar um em "Novo pacote de
						Exames".
					</div>

					<div
						v-for="pacote in pacotesDisponiveis"
						:key="pacote.id"
						class="flex items-center p-1">
						<input
							type="checkbox"
							:id="'pacote-' + pacote.id"
							:value="pacote.id"
							v-model="pacotesSelecionadosIds"
							class="mr-2 h-4 w-4" />
						<label :for="'pacote-' + pacote.id" class="text-gray-700">{{
							pacote.name
						}}</label>
					</div>
				</div>

				<div class="flex justify-end pt-4 border-t mt-4">
					<button
						@click="fechar"
						type="button"
						class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
						Cancelar
					</button>
					<button
						@click="adicionar"
						type="button"
						class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
						Adicionar Selecionados
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "ModalSelecionarPacote",

	props: {
		// Controla a visibilidade (enviado pelo "pai")
		show: {
			type: Boolean,
			default: false,
		},
		// Lista de pacotes (enviada pelo "pai")
		pacotesDisponiveis: {
			type: Array,
			default: () => [],
		},
	},

	data() {
		return {
			// Estado interno do formulário
			pacotesSelecionadosIds: [], // Array de IDs (ex: [1, 3])
		}
	},

	methods: {
		fechar() {
			// 1. Limpa a seleção
			this.pacotesSelecionadosIds = []

			// 2. Avisa o "pai" para fechar
			this.$emit("close")
		},

		adicionar() {
			// 1. Encontra os objetos completos dos pacotes selecionados
			const pacotesParaAdicionar = this.pacotesDisponiveis.filter((pacote) =>
				this.pacotesSelecionadosIds.includes(pacote.id)
			)

			// 2. Envia os objetos para o componente "pai"
			this.$emit("pacotes-adicionados", pacotesParaAdicionar)

			// 3. Fecha o modal
			this.fechar()
		},
	},
}
</script>
