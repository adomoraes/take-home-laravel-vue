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

	getPacotes() {
		return apiClient.get("/pacotes")
	},

	createPacote(pacoteData) {
		return apiClient.post("/pacotes", pacoteData)
	},

	gerarPdf(idsParaImpressao) {
		return apiClient.post("/gerar-impressao", idsParaImpressao, {
			responseType: "blob",
		})
	},
}
