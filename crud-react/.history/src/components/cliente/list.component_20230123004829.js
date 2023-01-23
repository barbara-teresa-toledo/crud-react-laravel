import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import Button from "react-bootstrap/Button";
import axios from "axios";
import Swal from "sweetalert2";

export default function List() {
  const [clientes, setClientes] = useState([]);

  useEffect(() => {
    fetchClientes();
  }, []);

  const fetchClientes = async () => {
    await axios.get(`http://localhost:8000/api/clientes`).then(({ data }) => {
      setClientes(data);
    });
  };

  const deleteCliente = async (id) => {
    const isConfirm = await Swal.fire({
      title: "Atenção!",
      text: "Esta ação não pode ser revertida.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sim, excluir.",
      cancelButtonText: "Cancelar.",
    }).then((result) => {
      return result.isConfirmed;
    });

    if (!isConfirm) {
      return;
    }

    await axios
      .delete(`http://localhost:8000/api/clientes/${id}`)
      .then(({ data }) => {
        Swal.fire({
          icon: "success",
          text: data.message,
        });
        fetchClientes();
      })
      .catch(({ response: { data } }) => {
        Swal.fire({
          text: data.message,
          icon: "error",
        });
      });
  };

  return (
    <div className="container">
      <div className="row">
        <div className="col-12">
          <Link
            className="btn btn-primary mb-2 float-end"
            to={"/cliente/create"}
          >
            Novo Cliente
          </Link>
        </div>
        <div className="col-12">
          <div className="card card-body">
            <div className="table-responsive">
              <table className="table table-bordered mb-0 text-center">
                <thead>
                  <tr>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Endereço</th>
                    <th>Opções</th>
                  </tr>
                </thead>
                <tbody>
                  {clientes.length > 0 &&
                    clientes.map((row, key) => (
                      <tr key={key}>
                        <td>{row.nome}</td>
                        <td>{row.telefone}</td>
                        <td>{row.endereco}</td>
                        <td>
                          <Link
                            to={`/cliente/edit/${row.id}`}
                            className="btn btn-success me-2"
                          >
                            Editar Cliente
                          </Link>
                          <Button
                            variant="danger"
                            onClick={() => deleteCliente(row.id)}
                          >
                            Excluir
                          </Button>
                        </td>
                      </tr>
                    ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
