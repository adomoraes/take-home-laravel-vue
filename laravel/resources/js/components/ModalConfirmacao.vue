<template>
	<div
		v-if="show"
		class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
		<div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
			<div class="p-4 border-b">
				<h3 class="text-lg font-semibold text-dark">{{ titulo }}</h3>
			</div>

			<div class="p-6">
				<p class="text-gray-700">{{ mensagem }}</p>
			</div>

			<div class="flex justify-end p-4 bg-gray-50 rounded-b-lg">
				<button
					@click="$emit('close')"
					type="button"
					class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
					Cancelar
				</button>
				<button
					@click="$emit('confirm')"
					type="button"
					class="bg-primary hover:bg-dark-accent text-white font-bold py-2 px-4 rounded">
					Confirmar
				</button>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "ModalConfirmacao",
	props: {
		show: { type: Boolean, default: false },
		titulo: { type: String, default: "Confirmar Ação" },
		mensagem: { type: String, default: "Tem certeza que deseja continuar?" },
	},
}
</script>
