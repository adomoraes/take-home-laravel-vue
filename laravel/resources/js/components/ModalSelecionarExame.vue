<template>
	<div
		v-if="show"
		class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
		<div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
			<div class="flex justify-between items-center p-4 border-b">
				<h3 class="text-lg font-semibold">Selecionar Exames Avulsos</h3>
				<button
					@click="fechar"
					class="text-gray-500 hover:text-gray-800 text-2xl font-bold">
					&times;
				</button>
			</div>

			<div class="p-4">
				<div class="border rounded p-2 h-64 overflow-y-auto bg-gray-50">
					<div v-if="examesDisponiveis.length === 0" class="text-gray-500 p-2">
						Nenhum exame cadastrado.
					</div>

					<div
						v-for="exame in examesDisponiveis"
						:key="exame.id"
						class="flex items-center p-1">
						<input
							type="checkbox"
							:id="'exame-avulso-' + exame.id"
							:value="exame.id"
							v-model="examesSelecionadosIds"
							class="mr-2 h-4 w-4" />
						<label :for="'exame-avulso-' + exame.id" class="text-gray-700"
							>{{ exame.name }} ({{ exame.group }})</label
						>
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
	name: "ModalSelecionarExame",

	props: {
		show: {
			type: Boolean,
			default: false,
		},
		examesDisponiveis: {
			type: Array,
			default: () => [],
		},
	},

	data() {
		return {
			examesSelecionadosIds: [],
		}
	},

	methods: {
		fechar() {
			this.examesSelecionadosIds = []
			this.$emit("close")
		},

		adicionar() {
			const examesParaAdicionar = this.examesDisponiveis.filter((exame) =>
				this.examesSelecionadosIds.includes(exame.id)
			)

			this.$emit("exames-adicionados", examesParaAdicionar)

			this.fechar()
		},
	},
}
</script>
