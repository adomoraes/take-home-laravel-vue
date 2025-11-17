<template>
	<div>
		<h1 class="text-3xl font-bold text-dark mb-6">Dashboard</h1>

		<div class="grid grid-cols-1 md:grid-cols-3 md:grid-rows-2 gap-4">
			<router-link
				to="/solicitacao"
				class="bento-box md:col-span-2 md:row-span-2 bg-primary text-white hover:bg-dark-accent">
				<div class="flex flex-col justify-between h-full">
					<div>
						<h2 class="text-2xl font-bold mb-2">Solicitar Exames</h2>
						<p class="opacity-90">
							Iniciar uma nova solicitação, adicionar exames avulsos ou
							selecionar pacotes.
						</p>
					</div>
					<span class="text-lg font-semibold self-end">Ir &rarr;</span>
				</div>
			</router-link>

			<router-link
				to="/pacotes"
				class="bento-box bg-white text-dark hover:bg-light-bg">
				<h2 class="text-xl font-bold mb-2">Gestão de Pacotes</h2>
				<p class="text-sm opacity-70">
					Criar, editar ou remover pacotes de exames pré-definidos.
				</p>
				<span class="text-lg font-semibold self-end mt-4">&rarr;</span>
			</router-link>

			<router-link
				to="/exames"
				class="bento-box bg-white text-dark hover:bg-light-bg">
				<h2 class="text-xl font-bold mb-2">Gestão de Exames</h2>
				<p class="text-sm opacity-70">
					Cadastrar, editar ou remover exames individuais do sistema.
				</p>
				<span class="text-lg font-semibold self-end mt-4">&rarr;</span>
			</router-link>
		</div>
	</div>
</template>

<script>
export default {
	name: "DashboardHome",
}
</script>

<style scoped>
.bento-box {
	@apply rounded-xl shadow-lg p-6 transition-all duration-300 ease-in-out transform hover:scale-[1.02] flex flex-col justify-between;
}
</style>
