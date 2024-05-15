const bdFormat = function(num, fractionDigits = 2) {

    const formatter = new Intl.NumberFormat('bn-Bd', {
        minimumFractionDigits: fractionDigits,
        maximumFractionDigits: fractionDigits,
    });

    const bnToEng = function(bnNumber) {
        return bnNumber.replace(/[০-৯]/g, function(bnDigit) {
            return "০১২৩৪৫৬৭৮৯".indexOf(bnDigit);
        });
    }

    const engToBn = function(num) {
        return num.replace(/\d/g, function(d) {
            return "০১২৩৪৫৬৭৮৯"[d];
        });
    }

    return bnToEng(formatter.format(num));
}
