// Hàm khởi tạo, tất cả các trang đều cần
function khoiTao() {
    setupEventTaiKhoan();
    capNhatThongTinUser();

    document.getElementsByClassName('cart-number')[0].innerHTML = getSoLuongGioHang();
}

// ========= Các hàm liên quan tới danh sách sản phẩm =========
// copy 1 object, do trong js ko có tham biến , tham trị rõ ràng
// nên dùng bản copy để chắc chắn ko ảnh hưởng tới bản chính
function copyObject(o) {
    return JSON.parse(JSON.stringify(o));
}

// ================ Cart Number + Thêm vào Giỏ hàng ======================
function getListGioHang() {
    return JSON.parse(localStorage.getItem('giohang'));
}

function setListGioHang(list) {
    localStorage.setItem('giohang', JSON.stringify(list));
}

function addToGioHang(masp) {
    let currentList = getListGioHang();

    if(!currentList) {
        currentList = [];
    }

    let daCo = false;
    for(let sp of currentList) {
        if(sp.masp === masp) {
            sp.soLuong++;
            daCo = true;
        }
    }

    if(!daCo) {
        currentList.push({
            masp: masp,
            soLuong: 1
        })
    }

    setListGioHang(currentList);
}

function getSoLuongGioHang() {
    let currentList = getListGioHang();

    let soLuong = 0;
    if(currentList != null) {
        for(let sp of currentList) {
            soLuong += sp.soLuong;
        }
    }
    return soLuong+"";
}

function animateCartNumber() {
    // Hiệu ứng cho icon giỏ hàng
    let cn = document.getElementsByClassName('cart-number')[0];
    cn.style.transform = 'scale(2)';
    cn.style.backgroundColor = 'rgba(255, 0, 0, 0.8)';
    cn.style.color = 'white';
    cn.innerHTML = getSoLuongGioHang();
    setTimeout(function() {
        cn.style.transform = 'scale(1)';
        cn.style.backgroundColor = 'transparent';
        cn.style.color = 'red';
    }, 1200);
}

function themVaoGioHang(masp, tensp) {
    getCurrentUser((user) => {
        if(user && user.TrangThai === 0) {
            Swal.fire({
                title: 'Tài Khoản Bị Khóa!',
                text: 'Tài khoản của bạn hiện đang bị khóa nên không thể thêm hàng!',
                type: 'error',
                grow: 'row',
                confirmButtonText: 'Trở về',
                footer: '<a href>Liên hệ với Admin</a>'
            });
        } else {
            addToGioHang(masp);
            animateCartNumber();
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                type: 'success',
                html: ' Đã thêm <strong>' + tensp + '</strong> vào giỏ.',
                showConfirmButton: true,
                timer: 5000
            })
        }


    }, (error) => {
        console.log(error.responseText)
    })

    return false;
}

// ============================== TÀI KHOẢN ============================

// Hàm get set cho người dùng hiện tại đã đăng nhập
function getCurrentUser(onSuccess, onFail) {
    $.ajax({
        type: "POST",
        url: "api/users",
        dataType: "json",

        timeout: 5000, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
        data: {
            function: "loginUserInformation"
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            if(onSuccess) onSuccess(data);
        },
        error: function(e) {
            if(onFail) onFail(e);
        }
    })
}

// Hiển thị form tài khoản, giá trị truyền vào là true hoặc false
function showTaiKhoan(show) {
    let value = (show ? "scale(1)" : "scale(0)");
    let div = document.getElementsByClassName('containTaikhoan')[0];
    div.style.transform = value;
}

// Check xem có ai đăng nhập hay chưa (CurrentUser có hay chưa)
// Hàm này chạy khi ấn vào nút tài khoản trên header
function checkTaiKhoan() {
    getCurrentUser((data) => {
        if(!data) {
            showTaiKhoan(true);
        }
    }, (error) => {
        console.log(error);
    })
}

//  ================================ WEB 2 =================================
function checkDangKy() {
    let email = document.getElementById('email').value;
    let username = document.getElementById('newUser').value;
    let pass = document.getElementById('newPass').value;
    $.ajax({
        url: "api/users",
        type: "post",
        dataType: "json",
        timeout: 5000,
        data: {
            function: 'register',
            email: email,
            username: username,
            password: pass,
        },
        success: function(kq) {
            console.log(kq);
            if(!kq.success)
            {
                let text = "";
                for (let m of kq.message) {
                    text += m + "\n";
                }
                Swal.fire({
                    type: 'error',
                    title: 'Chưa đăng ký xong',
                    text: text,
                    confirmButtonText: "Xong"
                });
            }
        },
        error: function(e) {
            Swal.fire({
                type: 'success',
                title: 'Đăng kí thành công',
                text: 'Vui lòng xác nhận email.',
                confirmButtonText: "Xong"
            }).then(() => {
                //     capNhatThongTinUser();
                showTaiKhoan(false);
            });
        }
    });
    grecaptcha.reset();
    return false;
}

function checkDangNhap() {
    let a = document.getElementById('username');
    let b = document.getElementById('pass');

    $.ajax({
        url: "api/users",
        type: "post",
        dataType: "json",
        timeout: 5000,
        data: {
            function: 'login',
            username: a.value,
            password: b.value,
            "g-recaptcha-response":grecaptcha.getResponse()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            if(data.success) {
                Swal.fire({
                    type: "success",
                    title: "Đăng nhập thành công",
                    text: "Chào " + data.user.Ho + " " + data.user.Ten
                }).then(() => {
                    capNhatThongTinUser();
                });
                showTaiKhoan(false);
                a.value="";
                b.value="";
            } else {
                let title = "";
                for (let m of data.message) {
                    title += m + "\n";
                }
                Swal.fire({
                    type: "error",
                    title: title
                });
            }
        },
        error: function(e) {
            Swal.fire({
                type: "error",
                title: "Lỗi khi đăng nhập",
                // html: e.responseText
            });
            console.log(e.responseText)
        }
    });
    grecaptcha.reset();
    return false;
}

function checkDangXuat(onSuccess) {
    Swal.fire({
        type: 'question',
        title: 'Xác nhận',
        text: 'Bạn có chắc muốn đăng xuất?',
        showCancelButton: true,
        confirmButtonText: 'Đồng ý',
        cancelButtonText: 'Hủy'

    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "api/users",
                dataType: "text",
                timeout: 5000,
                data: {
                    function: 'logout',
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if(data === 'ok') {
                        Swal.fire({
                            type: "success",
                            title: "Đăng xuất thành công"
                        }).then(() => {
                            capNhatThongTinUser();
                            setListGioHang(null);
                            animateCartNumber();
                        });

                        if(onSuccess) onSuccess();

                    } else {
                        Swal.fire({
                            type: "error",
                            title: "Chưa có ai đăng nhập"
                        })
                    }
                },
                error: function(e) {
                    Swal.fire({
                        type: "error",
                        title: "Có lỗi khi đăng xuất",
                        // html: e.responseText
                    })
                    console.log(e.responseText)
                }
            })
        }
    });
}

function capNhatThongTinUser() {
    getCurrentUser((data) => {
        if(!data) {
            document.getElementById("btnTaiKhoan").innerHTML = '<i class="fa fa-user"></i> Tài khoản';
            document.getElementsByClassName("menuMember")[0].classList.add('hide');

        } else {
            document.getElementById("btnTaiKhoan").innerHTML = '<i class="fa fa-user"></i> ' + data['TaiKhoan'];
            document.getElementsByClassName("menuMember")[0].classList.remove('hide');
        }
    })
}

function promoToWeb(name, value) { // khuyen mai
    if (!name || name === "Nothing") return "";
    let contentLabel = "";
    switch (name) {
        case "GiamGia":
            contentLabel = `<i class="fa fa-bolt"></i> Giảm ` + value.toLocaleString() + `&#8363;`;
            break;

        case "TraGop":
            contentLabel = `Trả góp ` + value.toLocaleString() + `%`;
            break;

        case "GiaReOnline":
            contentLabel = `Giá rẻ online`;
            break;

        case "MoiRaMat":
            contentLabel = "Mới ra mắt";
            break;
    }

    return `<label class=` + name.toLowerCase() + `>
            ` + contentLabel + `
        </label>`;
}

//  ================================ END WEB 2 =================================

// Tạo event, hiệu ứng cho form tài khoản
function setupEventTaiKhoan() {
    let taikhoan = document.getElementsByClassName('taikhoan')[0];
    let list = taikhoan.getElementsByTagName('input');

    // Tạo eventlistener cho input để tạo hiệu ứng label
    // Gồm 2 event onblur, onfocus được áp dụng cho từng input trong list bên trên
    ['blur', 'focus'].forEach(function(evt) {
        for (let i = 0; i < list.length; i++) {
            list[i].addEventListener(evt, function(e) {
                let label = this.previousElementSibling; // lấy element ĐỨNG TRƯỚC this, this ở đây là input
                if (e.type === 'blur') { // khi ấn chuột ra ngoài
                    if (this.value === '') { // không có value trong input thì đưa label lại như cũ
                        label.classList.remove('active');
                        label.classList.remove('highlight');
                    } else { // nếu có chữ thì chỉ tắt hightlight chứ không tắt active, active là dịch chuyển lên trên
                        label.classList.remove('highlight');
                    }
                } else if (e.type === 'focus') { // khi focus thì label active + hightlight
                    label.classList.add('active');
                    label.classList.add('highlight');
                }
            });
        }
    })

    // Event chuyển tab login-signup
    let tab = document.getElementsByClassName('tab');
    for (let i = 0; i < tab.length; i++) {
        let a = tab[i].getElementsByTagName('a')[0];
        a.addEventListener('click', function(e) {
            e.preventDefault(); // tắt event mặc định

            // Thêm active(màu xanh lá) cho li chứa tag a này => ấn login thì login xanh, signup thì signup sẽ xanh
            this.parentElement.classList.add('active');

            // Sau khi active login thì phải tắt active sigup và ngược lại
            // Trường hợp a này thuộc login => <li>Login</li> sẽ có nextElement là <li>SignUp</li>
            if (this.parentElement.nextElementSibling) {
                this.parentElement.nextElementSibling.classList.remove('active');
            }
            // Trường hợp a này thuộc signup => <li>SignUp</li> sẽ có .previousElement là <li>Login</li>
            if (this.parentElement.previousElementSibling) {
                this.parentElement.previousElementSibling.classList.remove('active');
            }

            // Ẩn phần nhập của login nếu ấn signup và ngược lại
            // href của 2 tab signup và login là #signup và #login -> tiện cho việc getElement dưới đây
            let target = this.href.split('#')[1];
            document.getElementById(target).style.display = 'block';

            let hide = (target === 'login' ? 'signup' : 'login');
            document.getElementById(hide).style.display = 'none';
        })
    }

    // Đoạn code tạo event trên được chuyển về js thuần từ code jquery
    // Code jquery cho phần tài khoản được lưu ở cuối file này
}

// ==================== Những hàm khác =====================
function numToString(num, char) {
    return num.toLocaleString().split(',').join(char || '.');
}

function stringToNum(str, char) {
    return Number(str.split(char || '.').join(''));
}

// https://www.w3schools.com/howto/howto_js_autocomplete.asp
function autocomplete(inp, arr) {
    let currentFocus;

    inp.addEventListener("keyup", function(e) {
        if (e.keyCode !== 13 && e.keyCode !== 40 && e.keyCode !== 38) { // not Enter,Up,Down arrow
            let a, b, i, val = this.value;

            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) {
                return false;
            }
            currentFocus = -1;

            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");

            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);

            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/
                if (arr[i].name.substr(0, val.length).toUpperCase() === val.toUpperCase()) {

                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");

                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].name.substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].name.substr(val.length);

                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i].name + "'>";

                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function() {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        inp.focus();

                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        }

    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        let x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode === 40) {
            /*If the arrow DOWN key is pressed, increase the currentFocus letiable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode === 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus letiable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode === 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/

            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) {
                    x[currentFocus].click();
                    e.preventDefault();
                }
            }
        }
    });

    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (let i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }

    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document, except the one passed as an argument:*/
        let x = document.getElementsByClassName("autocomplete-items");
        for (let i = 0; i < x.length; i++) {
            if (elmnt !== x[i] && elmnt !== inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function(e) {
        closeAllLists(e.target);
    });
}

// Thêm từ khóa tìm kiếm
function addTags(nameTag, link) {
    let new_tag = `<a href=` + link + `>` + nameTag + `</a>`;

    // Thêm <a> vừa tạo vào khung tìm kiếm
    let khung_tags = document.getElementsByClassName('tags')[0];
    khung_tags.innerHTML += new_tag;
}

function smallmenu(number) {
    if (number === 1) {
        document.getElementById("openmenu").style.display = "none";
        document.getElementById("closemenu").style.display = "block";
        document.getElementsByClassName("content")[0].style.maxHeight = document.getElementsByClassName("content")[0].scrollHeight + "px";
        document.getElementsByClassName("content")[0].style.overflow = "unset";

    } else if (number === 0) {
        document.getElementById("openmenu").style.display = "block";
        document.getElementById("closemenu").style.display = "none";
        document.getElementsByClassName("content")[0].style.maxHeight = null;
        document.getElementsByClassName("content")[0].style.overflow = "hidden";
    }
}
// Di chuyển lên đầu trang
function gotoTop() {
    if (window.jQuery) {
        jQuery('html,body').animate({
            scrollTop: 0
        }, 1000);
    } else {
        document.getElementsByClassName('top-nav')[0].scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
}

function gotoBot() {
    if (window.jQuery) {
        jQuery('html,body').animate({
            scrollTop: $(document).height()
        }, 1000);
    } else {
        document.getElementsByClassName('footer')[0].scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
}
function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}

/**
 * …and then create the method to output the date string as desired.
 * Some people hate using prototypes this way, but if you are going
 * to apply this to more than one Date object, having it as a prototype
 * makes sense.
 **/
Date.prototype.toMysqlFormat = function() {
    return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(this.getUTCDate()) + " " + twoDigits(this.getUTCHours()) + ":" + twoDigits(this.getUTCMinutes()) + ":" + twoDigits(this.getUTCSeconds());
};
