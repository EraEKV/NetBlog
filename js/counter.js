


const numbers = document.querySelectorAll(".number")

numbers.forEach(element => {
    element.innerHTML = "0"

    let changeNum = () => {
        let value = +element.innerHTML
        let finalNum = element.getAttribute("data-num")
        // if(finalNum < 100 && finalNum > 60) {
        //     step = Math.ceil(+finalNum / 10)
        // }

        let step = Math.ceil(+finalNum / 10)

        if(value < finalNum) {
            value += step
            element.innerHTML = value
            setTimeout(changeNum, 80)
        }
        else if(value >= finalNum) {
            element.innerHTML = `${finalNum}+`
        }
    }

    changeNum()
})