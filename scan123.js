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
// ==============================================================================================
// =================================== Helper Functions =========================================
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

// sorts 2 strings alphabetically. if they're the same, returns false
function alphabetical(s1, s2){
    if (typeof s1 !== 'string' || typeof s2 !== 'string') {
        console.log('Error: Function "alphabetical" only accepts strings as arguments')
        return undefined
    }
    // if they're the same, just return false
    if (s1 === s2){
        return false
    }
    let sorted = [s1,s2].sort()
    return sorted[0]
}
// sorts 2 strings numerically. if they're both undefined, returns false
function numerical(s1, s2){
    //if they are not undefined and they match
    if (s1 === s2 && (s1 !== undefined)){
        return false
    }
    //if both are valid (but do not match)
    if(s1 || s2){
        let sorted = [s1,s2].sort()
        return sorted[0]
    }
}
// ==============================================================================================
// =================================== Main Function ============================================
function dynamic_sort(arr, type, order){
    // base case
    if(arr.length <= 1){
        return arr
    }

    let temp = arr[0]
    let pivot = createObj(temp)
    let lowers = [];
    let highers = [];

    // arr.forEach((item, i) => {
    for(let i=1; i<arr.length; i++){
        let item = arr[i];
        let iObj = createObj(item, i)
        // I think instead of pushing directly, it might be better later to keep these as variables
        // it may ease flexibility when adjusting for different orders based on the arguments
        let lesser;
        // according to the type...
        switch(type){
            case 'num':
                let sortedByYear = numerical(iObj.year, pivot.year)
                if(sortedByYear){
                    // if at least 1 of them has a valid year expressed
                    // see if the lesser year is the object year
                    lesser = (iObj.year === sortedByYear)
                    break;
                } else {
                    let sortedAlpha = alphabetical(iObj.make, pivot.make)
                    // True edge case: have same make and year, so it doesnt matter. I dont think this can even happen
                    if(!sortedAlpha){
                        lesser = true
                        break;
                    } else {
                        lesser = (sortedAlpha === iObj.make)
                        break;
                    }
                }

            case 'alph':
                // Go alphabetically
                let sortedAlpha = alphabetical(iObj.make, pivot.make)
                // edge case: have same make name. So go by Year
                if(sortedAlpha){
                    lesser = (sortedAlpha === iObj.make);
                    break;
                }
                if(iObj.make === pivot.make){
                    // if they have the same make name, sort it by year.
                    let sortedByYear = numerical(iObj.year, pivot.year)
                    if(sortedByYear){
                    // if at least 1 of them has a valid year expressed
                    // see if the lesser year is the object year
                        lesser = (iObj.year === sortedByYear)
                        break;
                    } else {
                        // true edge case. same make and year. this likely wont ever happen unless another error is going on
                        lesser = true;
                        break;
                    }
                }

            default:
                console.log('Error: not a valid sorting type');
                return 'Error: not a valid sorting type';
        }

        // then to the order...
        switch(order){
            case 'asc':
                if(lesser){ lowers.push(item) }
                else { highers.push(item) }
                break;
            case 'desc':
                if(!lesser){ highers.push(item) }
                else { lowers.push(item) }
                break;
        }
    }
    // might have to remove buick from the array. it might be getting stuck as the 'permanent temp'
    // might have to reverse the order of these depending on the type of order

    switch(order){
        case 'asc':
            return [...dynamic_sort(lowers, type, order), temp, ...dynamic_sort(highers, type, order)]
        case 'desc':
            return [...dynamic_sort(highers, type, order), temp, ...dynamic_sort(lowers, type, order)]
    }

}


console.log(' ===== by year - ascending ===== ')
console.log(dynamic_sort(makes, 'num','asc')); // by year - ascending
console.log(' ===== by year - descending ===== ');
console.log(dynamic_sort(makes, 'num','desc')); // by year - ascending
console.log(' ===== alphabetically - ascending ===== ');
console.log(dynamic_sort(makes, 'alph','asc')); // alphabetically - ascending
console.log(' ===== alphabetically - descending ===== ');
console.log(dynamic_sort(makes, 'alph','desc')); // alphabetically - descending
