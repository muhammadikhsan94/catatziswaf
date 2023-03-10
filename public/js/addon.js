//konversi uang ribuan
function format_uang(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

