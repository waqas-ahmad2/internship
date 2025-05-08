function OpenModal(btn, btnelement=null){
    document.getElementById('modal').style.display = 'block'
    document.getElementById("modalTitleAdd").style.display='none'
    document.getElementById("modalTitleUpdate").style.display = 'none'
    document.getElementById('updateModalBtn').style.display = 'none'
    document.getElementById('submitBtn').style.display = 'none'

    if(btn === "addvoucher"){
        document.getElementById('submitBtn').style.display = 'block'
        document.getElementById("modalTitleAdd").style.display='block'
    }

    else if(btn === 'editvoucher'){
        document.getElementById('updateModalBtn').style.display = 'block'
        document.getElementById("modalTitleUpdate").style.display='block'

        let wrapper = btnelement.closest('.wrapper')
        let form = wrapper.querySelector('form')
        let formData = new FormData(form)

        document.getElementById('studentId').value = formData.get('std_id')
        document.getElementById('invoiceNo').value = formData.get('invoice')
        document.getElementById('date').value = formData.get('date')
        document.getElementById('monthlyFee').value = formData.get('monthly')
        document.getElementById('uniformFee').value = formData.get('uniform')
        document.getElementById('sportsFee').value = formData.get('sport')
        document.getElementById('fine').value = formData.get('fine')
    }

    //detail view
    else if(btn === 'viewvoucher'){
        OpenReceipt(btnelement)
    }
}

function CloseModal(){
    document.getElementById('modal').style.display ='none'
}

function OpenReceipt(btnelement) {
    document.getElementById('modal').style.display = 'none';
    document.getElementById('receiptModal').style.display = 'block';

    let wrapper = btnelement.closest('.wrapper');
    let form = wrapper.querySelector('form');
    let formData = new FormData(form);

    let studentId = formData.get('std_id');
    let invoiceNo = formData.get('invoice');
    let date = formData.get('date');
    let monthly = formData.get('monthly');
    let uniform = formData.get('uniform');
    let sport = formData.get('sport');
    let fine = formData.get('fine');
    let total = formData.get('total');

    document.getElementById('r_studentId').innerText = studentId;
    document.getElementById('hidden_invoice').value = invoiceNo;
    document.getElementById('r_invoiceNo').innerText = invoiceNo;
    document.getElementById('r_date').innerText = date;
    document.getElementById('r_monthlyFee').innerText = monthly;
    document.getElementById('r_uniformFee').innerText = uniform;
    document.getElementById('r_sportsFee').innerText = sport;
    document.getElementById('r_fine').innerText = fine;
    document.getElementById('r_totalAmount').innerText = total;
}


function CloseReceipt(){
    document.getElementById('receiptModal').style.display ='none'
}

function fetchphp(){
    fetch(`api.php`)
    .then(res=>res.json())
    .then(data=>{
        const display = document.getElementById('display');
        if(data.length === 0){
            display.innerHTML =  '<p>no data found</p>'
            return;
        }

        let showFields = ['invoice_no', 'Date', 'Total Amount'];
        let table = `<table class="table table-hover"><thead><tr>`
    
        showFields.forEach(element => {
            table += `<th>${element}</th>
            `
        });


        table += `<th>Action</th>
        </tr></thead><tbody>`

        let count = 1

        data.forEach(row =>{
            table += `<tr>`
            showFields.forEach(element =>{

                table += `<td>${row[element]}</td>`
            })

            table += `<td class='wrapper'>
            <form action="server.php" method="POST" style="display:none;">
                <input type="hidden" name="id" value="${count}">
                <input type="hidden" name="std_id" value="${row["student_id"]}">
                <input type="hidden" name="invoice" value="${row['invoice_no']}">
                <input type="hidden" name="date" value="${row['Date']}">
                <input type="hidden" name="monthly" value="${row["Monthly Fee"]}">
                <input type="hidden" name="uniform" value="${row['Uniform Fee']}">
                <input type="hidden" name="sport" value="${row['Sports Fee']}">
                <input type="hidden" name="fine" value="${row['Fine']}">
                <input type="hidden" name="total" value="${row['Total Amount']}">
            </form>
            <button type="button" class="btn btn-info" onclick="OpenModal('editvoucher', this)">Edit</button>
            <button type="button" class="btn btn-warning" onclick="OpenModal('viewvoucher',this)">View</button>
            </td>
            </tr>`
            count +=1
        })

        table += `</tr></thead><tbody><tr>`

        display.innerHTML = table

    })
    .catch(err => {
        display.innerHTML = `<p>Error fetching data</p>`;
        console.error(err);
    })
}



document.addEventListener('DOMContentLoaded',()=>{
    fetchphp()

    window.addEventListener('click',(e)=>{
        if(e.target.classList.contains('modal')){

            e.target.style.display = "none"
        }
    })
})

//search bar
let search = document.getElementById('searchbar')
search.addEventListener('input',(e)=>{
    let text = e.target.value.toLowerCase()
    let all_data = document.querySelectorAll('#display table tbody tr')

    all_data.forEach(row=>{
        let rowtext = row.innerText.toLowerCase()
        row.style.display = rowtext.includes(text)?'':'none'

    })

})
