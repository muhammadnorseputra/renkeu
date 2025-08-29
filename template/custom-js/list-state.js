(function () {
	const STORAGE_KEY = "listjs_state";
	const SAVE_DELAY = 300; // delay 300ms

	// Debounce utility
	function debounce(func, delay) {
		let timer;
		return function (...args) {
			clearTimeout(timer);
			timer = setTimeout(() => func.apply(this, args), delay);
		};
	}

	// Ambil semua state
	function loadStates() {
		return JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}");
	}

	// Simpan semua state dengan debounce
	const saveStates = debounce(function (states) {
		localStorage.setItem(STORAGE_KEY, JSON.stringify(states));
	}, SAVE_DELAY);

	// Terapkan state ke list
	function applyState(list, state) {
		if (!state) return;
		if (state.search) {
			list.search(state.search);
			const searchInput = list.listContainer.querySelector(".search");
			if (searchInput) searchInput.value = state.search;
		}
		if (state.sort) {
			list.sort(state.sort.valueName, { order: state.sort.order });
		}
		if (state.page && list.page) {
			list.show((state.page - 1) * list.page + 1, list.page);
		}
	}

	// Simpan instance asli List
	const OriginalList = window.List;

	// Override List untuk auto save state
	window.List = function (container, options, values) {
		// Pastikan container adalah element
		let containerEl =
			typeof container === "string"
				? document.getElementById(container)
				: container;
		if (!containerEl) {
			console.error("List.js container tidak ditemukan:", container);
			return new OriginalList(container, options, values);
		}

		// Buat list instance
		const list = new OriginalList(containerEl, options, values);

		// Load state
		const states = loadStates();
		const listId =
			containerEl.id || "list_" + Math.random().toString(36).substr(2, 5);

		containerEl.dataset.listId = listId;
		applyState(list, states[listId]);

		// Event: simpan search
		const searchInput = containerEl.querySelector(".search");
		if (searchInput) {
			searchInput.addEventListener("input", () => {
				states[listId] = states[listId] || {};
				states[listId].search = searchInput.value;
				saveStates(states);
			});
		}

		// Event: simpan sort
		const sortButtons = containerEl.querySelectorAll(".sort");
		sortButtons.forEach((btn) => {
			btn.addEventListener("click", () => {
				const sortState = list.sortState();
				states[listId] = states[listId] || {};
				states[listId].sort = {
					valueName: sortState.valueName,
					order: sortState.order,
				};
				saveStates(states);
			});
		});

		// Event: simpan pagination
		list.on("updated", () => {
			if (list.page) {
				const currentPage = Math.ceil((list.i + 1) / list.page);
				states[listId] = states[listId] || {};
				states[listId].page = currentPage;
				saveStates(states);
			}
		});

		return list;
	};
})();
