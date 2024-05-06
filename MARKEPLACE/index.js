
//const btnAdquirir = document.getElementById("adquirir");
const resultados = document.getElementById("res");
const cuerpo = document.getElementById("cuerpo");
const select = document.getElementById("categorias");
const modal = document.querySelectorAll(".modal");


modal.forEach((m) => {
    const o = m.querySelector(".opcion");
    let btnAdquirir = o.querySelector(".adquirir");
    let div = m.previousElementSibling;
    let icono = div.querySelector(".icono");
    if (btnAdquirir != null) {
        btnAdquirir.addEventListener("click", (e) => {

            let id = btnAdquirir.dataset.id
            console.log(id);
            const formData = new FormData();
            formData.append("idContenido", id);

            fetch("adquirir.php", {

                method: "POST",
                body: formData
            })
                .then(res => {
                    if (res.ok) {
                        let texto = document.createElement("p");
                        texto.innerText = '"Pedido Pendiente"';
                        o.replaceChild(texto, btnAdquirir);

                        let alerta = document.createElement("div");
                        alerta.innerText = "Tu pedido se ha quedado registrado";
                        alerta.classList.add("alert", "alert-success");
                        o.appendChild(alerta);

                        let i = document.createElement("i");
                        i.classList.add("bi", "bi-check-circle-fill");
                        icono.classList.add("text-end")
                        icono.appendChild(i);
                        
                        setTimeout(() => {
                            alerta.classList.add("d-none");
                        }, 1500);



                    }
                });


        })
    }

});


select.addEventListener("change", function () {
    let s = select.value;
    window.location = window.location.pathname + "?idCategoria=" + s;

})








