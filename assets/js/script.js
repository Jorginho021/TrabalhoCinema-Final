 document.addEventListener("DOMContentLoaded", function () {
  console.log("Projeto Cinema carregado");

  // Modal Lightbox para imagens
  const modal = document.getElementById("imageModal");
  const modalImg = document.getElementById("modalImage");
  const closeBtn = document.querySelector(".close-modal");

  // Adicionar event listeners para todas as imagens de notícias
  const newsImages = document.querySelectorAll(".news-card img");
  newsImages.forEach((img) => {
    img.addEventListener("click", function () {
      modal.classList.add("active");
      modalImg.src = this.src;
      modalImg.alt = this.alt;
      document.body.style.overflow = "hidden";
    });
  });

  // Fechar modal ao clicar no X
  if (closeBtn) {
    closeBtn.addEventListener("click", function () {
      modal.classList.remove("active");
      document.body.style.overflow = "auto";
    });
  }

  // Fechar modal ao clicar fora da imagem
  if (modal) {
    modal.addEventListener("click", function (event) {
      if (event.target === modal) {
        modal.classList.remove("active");
        document.body.style.overflow = "auto";
      }
    });
  }

  // Fechar modal com tecla ESC
  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape" && modal.classList.contains("active")) {
      modal.classList.remove("active");
      document.body.style.overflow = "auto";
    }
  });
});
