// Mengambil elemen-elemen HTML yang akan digunakan.
const form = document.querySelector("#form-kegiatan");
const input = document.querySelector("#nama-kegiatan");
const dateInput = document.querySelector("#tanggal-kegiatan");
const list = document.querySelector("#daftar");
const errorMessage = document.querySelector("#pesan-error");
const emptyMessage = document.querySelector("#pesan-kosong");
const totalElement = document.querySelector("#total");
const doneElement = document.querySelector("#selesai");
const activeElement = document.querySelector("#belum");
const clearButton = document.querySelector("#hapus-semua");
const remainingElement = document.querySelector("#sisa-karakter");

let tasks = [];
let currentFilter = "all";
const MAX_LENGTH = 80;

// Menghitung dan menampilkan jumlah kegiatan.
function updateSummary() {
  const total = tasks.length;
  const done = tasks.filter(task => task.done).length;

  totalElement.textContent = total;
  doneElement.textContent = done;
  activeElement.textContent = total - done;
}

// Membuat ulang tampilan daftar berdasarkan data dan filter.
function renderTasks() {
  list.textContent = "";

  const filteredTasks = tasks.filter(task => {
    if (currentFilter === "active") return !task.done;
    if (currentFilter === "done") return task.done;
    return true;
  });

  filteredTasks.forEach(task => {
    const item = document.createElement("li");
    item.className = `task-item ${task.done ? "done" : ""}`;
    item.dataset.id = task.id;

    const checkbox = document.createElement("input");
    checkbox.type = "checkbox";
    checkbox.className = "toggle-task";
    checkbox.checked = task.done;
    checkbox.setAttribute("aria-label", "Tandai kegiatan selesai");

    const content = document.createElement("div");
    content.className = "task-content";

    const name = document.createElement("span");
    name.className = "task-name";
    name.textContent = task.name;

    const date = document.createElement("small");
    date.className = "task-date";
    date.textContent = task.date
      ? `Tanggal: ${new Date(`${task.date}T00:00:00`).toLocaleDateString("id-ID", {
          day: "2-digit", month: "long", year: "numeric"
        })}`
      : "Tanggal belum ditentukan";

    content.append(name, date);

    const removeButton = document.createElement("button");
    removeButton.type = "button";
    removeButton.className = "hapus delete-button";
    removeButton.textContent = "Hapus";
    removeButton.setAttribute("aria-label", `Hapus kegiatan ${task.name}`);

    item.append(checkbox, content, removeButton);
    list.append(item);
  });

  emptyMessage.hidden = filteredTasks.length > 0;
  updateSummary();
}

// Menampilkan sisa karakter ketika pengguna mengetik.
input.addEventListener("input", () => {
  remainingElement.textContent = `${MAX_LENGTH - input.value.length} karakter tersisa`;
  if (input.value.length > MAX_LENGTH) {
    errorMessage.textContent = `Nama kegiatan maksimal ${MAX_LENGTH} karakter.`;
  } else {
    errorMessage.textContent = "";
  }
});

// Event submit menangani klik tombol Tambah maupun tombol Enter.
form.addEventListener("submit", event => {
  event.preventDefault();

  const name = input.value.trim();

  if (name === "") {
    errorMessage.textContent = "Nama kegiatan wajib diisi.";
    input.focus();
    return;
  }

  if (name.length > MAX_LENGTH) {
    errorMessage.textContent = `Nama kegiatan maksimal ${MAX_LENGTH} karakter.`;
    input.focus();
    return;
  }

  tasks.push({
    id: Date.now(),
    name: name,
    date: dateInput.value,
    done: false
  });

  input.value = "";
  dateInput.value = "";
  errorMessage.textContent = "";
  remainingElement.textContent = `${MAX_LENGTH} karakter tersisa`;
  input.focus();
  renderTasks();
});

// Event delegation: menangani checkbox yang dibuat secara dinamis.
list.addEventListener("change", event => {
  if (!event.target.matches(".toggle-task")) return;

  const item = event.target.closest(".task-item");
  const id = Number(item.dataset.id);
  const task = tasks.find(task => task.id === id);

  if (!task) return;

  task.done = event.target.checked;
  renderTasks();
});

// Event delegation: menangani tombol Hapus pada setiap item.
list.addEventListener("click", event => {
  const button = event.target.closest(".hapus");
  if (!button) return;

  const item = button.closest(".task-item");
  const id = Number(item.dataset.id);

  tasks = tasks.filter(task => task.id !== id);
  renderTasks();
});

// Satu listener pada wadah filter untuk semua tombol filter.
document.querySelector(".filters").addEventListener("click", event => {
  const button = event.target.closest("button[data-filter]");
  if (!button) return;

  currentFilter = button.dataset.filter;

  document.querySelectorAll(".filter").forEach(filterButton => {
    filterButton.classList.toggle("active", filterButton === button);
  });

  renderTasks();
});

// Menghapus seluruh kegiatan setelah pengguna menyetujui konfirmasi.
clearButton.addEventListener("click", () => {
  if (tasks.length === 0) {
    alert("Belum ada kegiatan untuk dihapus.");
    return;
  }

  const confirmed = confirm("Apakah Anda yakin ingin menghapus semua kegiatan?");
  if (!confirmed) return;

  tasks = [];
  currentFilter = "all";
  document.querySelectorAll(".filter").forEach(button => {
    button.classList.toggle("active", button.dataset.filter === "all");
  });
  renderTasks();
});

// Menampilkan kondisi awal aplikasi.
renderTasks();
