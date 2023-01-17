import { Usuario } from 'Interface/usuario';

export interface Login {
  message: string;
  expires: string;
  usuario: Usuario;
  accessToken: string;
  refreshToken: string;
}

export interface Logout {
  message: string;
  url: string;
  code: number;
}

export interface Sessao {
  id: string;
  expiracao: string;
  usuario_id: number;
  dados: string;
  criacao: string;
  modificacao: string;
}
