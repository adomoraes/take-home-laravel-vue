// Ficheiro: resources/js/apiService.js

import axios from "axios"

// Configura o 'baseURL' para todas as chamadas axios
const apiClient = axios.create({
	baseURL: "/api", // Como o Vue e a API correm no mesmo domínio, só precisamos do caminho relativo.
	headers: {
		Accept: "application/json",
		"Content-Type": "application/json",
	},
})

export default {
	// --- Exames ---
	getExames() {
		return apiClient.get("/exames")
	},

	// --- Pacotes ---
	getPacotes() {
		return apiClient.get("/pacotes")
	},

	createPacote(pacoteData) {
		// pacoteData = { name: '...', observations: '...', exams: [1, 2] }
		return apiClient.post("/pacotes", pacoteData)
	},

	// --- Impressão ---
	gerarPdf(idsParaImpressao) {
		// idsParaImpressao = { exames: [1], pacotes: [1] }
		return apiClient.post("/gerar-impressao", idsParaImpressao, {
			// MUITO IMPORTANTE: Diga ao Axios para esperar um ficheiro (blob), não um JSON.
			responseType: "blob",
		})
	},
}
