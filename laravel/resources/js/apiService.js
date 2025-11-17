import axios from "axios"

const apiClient = axios.create({
	baseURL: "/api",
	headers: {
		Accept: "application/json",
		"Content-Type": "application/json",
	},
})

export default {
	getExames() {
		return apiClient.get("/exames")
	},

	createExame(exameData) {
		return apiClient.post("/exames", exameData)
	},

	// --- FUNÇÕES ADICIONADAS ---
	updateExame(id, exameData) {
		// O Laravel usa PUT ou PATCH para 'update'
		return apiClient.put(`/exames/${id}`, exameData)
	},
	deleteExame(id) {
		return apiClient.delete(`/exames/${id}`)
	},
	// -------------------------

	getPacotes() {
		return apiClient.get("/pacotes")
	},

	createPacote(pacoteData) {
		return apiClient.post("/pacotes", pacoteData)
	},
	// --- FUNÇÕES ADICIONADAS ---
	updatePacote(id, pacoteData) {
		return apiClient.put(`/pacotes/${id}`, pacoteData)
	},
	deletePacote(id) {
		return apiClient.delete(`/pacotes/${id}`)
	},
	// -------------------------

	gerarPdf(idsParaImpressao) {
		return apiClient.post("/gerar-impressao", idsParaImpressao, {
			responseType: "blob",
		})
	},
}
