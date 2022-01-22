let makes = [
    "Buick",
    "2019 Acura",
    "Fiat",
    "Chevrolet 2022",
    "2018 Chevrolet",
    "2019 Lambo",
    "2021 Chevrolet",
    "Datsun",
    "Audi",
    "Caddilac",
    "Chevrolet 2020"
]
let types = ['num', 'alph']
let orders = ['asc', 'desc']

function hasYear(string){
    let n = '0123456789'
    let itemArr = string.split(' ')
    if(itemArr.length < 2){ return false }
    let res = false
    itemArr.forEach(word => {
        if(n.includes(word[0])){
            res = word
        }
    })
    return res
}
function createObj(string, i){
    let n = '0123456789'
    let obj = {}
    obj.index = i;

    let wordArr = string.split(' ')
    wordArr.forEach(word => {
        if(n.includes(word[0])){
            obj.year = word
        } else if (!obj.make) {
            obj.make = word
        }
    })
    return obj
}

function dynamic_sort(arr, type, order){
    
    if(arr.length <= 1){
        return arr
    }

    let n = '0123456789'
    let arrObjects = [];

    arr.forEach((item, i) => {
        splitRes.push(createObj(item, i))
    })

    return arr.sort(function(a,b){
        // console.log(a, b)
        // if(hasYear(a) && hasYear(b)){
        //     console.log('scenario a')
        //     return a - b;
        // } 
        // else if (hasYear(a) || hasYear(b)) {
        //     console.log('scenario b')
        //     if (hasYear(a)){
        //         return a > b 
        //     } else {
        //         return b > a
        //     }
        // }
        // console.log('scenario c')
        return a-b
    })

}
let y = [4,9,4,3,'g',8,1,5,6]
console.log(y.sort(function(a,b){
    if(typeof a === 'string' && typeof b !== 'string'){
        console.log(a,b)
        return b-a
    }
    if(typeof a !== 'string' && typeof b === 'string'){
        console.log(a,b)
        return a-b
    } else {
        return a-b;
    }
}));
// console.log(hasYear(makes[0]))
// console.log(hasYear(makes[1]))
// console.log(hasYear(makes[2]))
// console.log(hasYear(makes[3]))
// console.log(hasYear(makes[4]))
// console.log(dynamic_sort(makes, types[0], orders[0]));
