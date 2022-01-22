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

// accepts a string
// if string contains a word that starts with an integer, returns integer
// otherwise returns false
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
// accepts a string, returns an object
// object.index => i, object.make => first word in string, object.year => first word starting with an int else underfined
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
    // base case
    if(arr.length <= 1){
        return arr
    }

    let n = '0123456789';
    let temp = arr[0]
    let pivot = createObj(arr.shift())
    let lowers = [];
    let highers = [];

    arr.forEach((item, i) => {
        let iObj = createObj(item, i)
        let lesser;
        let greater;
        // according to the type...
        if(type === 'num'){
            if(iObj.year && pivot.year){
                // find the lower year
                if(iObj.year < pivot.year){
                    lesser = item
                    greater = temp
                } else if (iObj.year >= pivot.year){ // <====== change this back to just 'greater than'
                    lesser = temp
                    greater = item
                } else {
                    let x = [iObj.make, pivot.make].sort()
                    lesser = x[0]
                    greater = x[1]
                }
            } else 
            // if only one has year, it goes first (or second)
            if(iObj.year || pivot.year){
                if(iObj.year){
                    lesser = item
                    greater = temp
                } else {
                    lesser = temp
                    greater = item
                }
            } else 
            // neither have year, so go alphabetically
            {

            }
        }
            // then to the order...
    })

    return [
        ...dynamic_sort(lowers, type, order),
        temp,
        ...dynamic_sort(highers, type, order)
    ]

}
// console.log(hasYear(makes[0]))
// console.log(hasYear(makes[1]))
// console.log(hasYear(makes[2]))
// console.log(hasYear(makes[3]))
// console.log(hasYear(makes[4]))
// console.log(dynamic_sort(makes, types[0], orders[0]));

// let y = ['allison','yolanda','alex','borris','netasha']
// console.log(['allison','yolanda','alex','borris','netasha'].sort())
// console.log(y.sort(function(a,b){
//     if(typeof a === 'string' && typeof b !== 'string'){
//         console.log(a,b)
//         return b-a
//     }
//     if(typeof a !== 'string' && typeof b === 'string'){
//         console.log(a,b)
//         return a-b
//     } else {
//         return a-b;
//     }
// }));