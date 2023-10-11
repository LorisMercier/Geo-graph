function preventNumberInputOverflow(input) {
    if (input.value == "") {
        //prevent empty field
        input.value = parseInt(input.defaultValue)
    }
    else if (input.value > parseInt(input.max)) {
        //prevent overflow
        input.value = input.max
    }
    else if (input.value < parseInt(input.min)) {
        //prevent underflow
        input.value = input.min
    }
    else {
        //prevent float values
        input.value = parseInt(input.value)
    }
}